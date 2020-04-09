@Async
    @Scheduled(fixedRate = 5000)
    public void file_checker() throws FileNotFoundException, UnsupportedEncodingException {
        // 1. Find a list of pending jobs
        List<FixFileJobQueue> fixFileJobQueuePending = fixFileJobQueueRepository.findPendingJob();

        if(!fixFileJobQueuePending.isEmpty()) {
            // Helpers
            String pattern_name = "($^|^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$)";
            String pattern_custom_var = "($^|^[a-zA-Z0-9]+(([',. -][a-zA-Z0-9 ])?[a-zA-Z0-9]*)*$)";
            String pattern_email = "($^|(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|\"(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21\\x23-\\x5b\\x5d-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21-\\x5a\\x53-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])+)\\]))";
            String pattern_mobile = "($^|^[0-9\\+][0-9]*$)";
            String pattern_id_number = "($^|^[0-9]*$)";

            Pattern r_name = Pattern.compile(pattern_name);
            Pattern r_custom_var = Pattern.compile(pattern_custom_var);
            Pattern r_email = Pattern.compile(pattern_email);
            Pattern r_mobile = Pattern.compile(pattern_mobile);
            Pattern r_id_number = Pattern.compile(pattern_id_number);

            // Variables
            List<CSVRecord> bad_rows = new ArrayList<>();
            List<CSVRecord> good_rows = new ArrayList<>();


            FixFileJobQueue fixFileJob = fixFileJobQueuePending.get(0);
            ClientUpload clientUpload = clientUploadRepository.findByUserId(fixFileJob.getUser_id()).get(0);
            Long uploads_left = clientUpload.getUpload_limit() - clientUpload.getUploads();

            fixFileJob.setStatus("processing");
            fixFileJobQueueRepository.saveAndFlush(fixFileJob);

            // 2. Download file and store it temporarily.
            
            S3Object s3object_tmp = upload_s3client.getObject("meetpat.fileuploads", "new_files/" + fixFileJob.getFile_uuid() + ".csv");
            S3ObjectInputStream inputStream_tmp = s3object_tmp.getObjectContent();
            // Fix temp file

            PrintWriter writer = new PrintWriter("./src/main/resources/temp/fixed_files/" + fixFileJob.getFile_uuid() + ".csv", "UTF-8");

            try {
                FileUtils.copyInputStreamToFile(inputStream_tmp, new File("./src/main/resources/temp/new_files/" + fixFileJob.getFile_uuid() + ".csv"));
            } catch (IOException e) {
                e.printStackTrace();
            }

            Reader in_new_file_scan = null;
            Reader in_new_file = null;

            try {
                // Create temp fixed file to upload afterwards.
                in_new_file_scan = new FileReader("./src/main/resources/temp/new_files/" + fixFileJob.getFile_uuid() + ".csv");

                Scanner scanner = new Scanner(in_new_file_scan);
                String headers = scanner.nextLine();

                String headers_set = "FirstName;Surname;MobilePhone;Email;IDNumber;CustomVar1";
                String splitter = null;

                if(headers.split(",").length == 6) {
                    headers_set = "FirstName,Surname,MobilePhone,Email,IDNumber,CustomVar1";
                    splitter = ",";
                } else if(headers.split(";").length == 6) {
                    splitter = ";";
                }

                // Check user upload limits first

                if(splitter.equals(";") || splitter.equals(",")) {
                    fixFileJob.setValid_csv(true);
                    fixFileJobQueueRepository.saveAndFlush(fixFileJob);

                    if(headers.equals(headers_set)) {

                        fixFileJob.setMatches_template(true);
                        fixFileJobQueueRepository.save(fixFileJob);
                        scanner.close();
                        in_new_file = new FileReader("./src/main/resources/temp/new_files/" + fixFileJob.getFile_uuid() + ".csv");
                        Iterable<CSVRecord> records = CSVFormat.EXCEL.withHeader().withDelimiter(';').parse(in_new_file);
                        System.out.println("Started checking rows");
                        records.spliterator().forEachRemaining(row -> {
                            System.out.println(row);
                            if(
                                    row.get("Email").isEmpty() && row.get("MobilePhone").isEmpty()
                                            || !r_name.matcher(row.get("FirstName")).find()
                                            || !r_name.matcher(row.get("Surname")).find()
                                            || !r_mobile.matcher(row.get("MobilePhone")).find()
                                            || !r_email.matcher(row.get("Email").toLowerCase()).find()
                                            || !r_id_number.matcher(row.get("IDNumber")).find()
                                            || !r_custom_var.matcher(row.get("CustomVar1")).find()
                            ) {
                                if(!bad_rows.contains(row)) {
                                    bad_rows.add(row);
                                }
                            } else {
                                if(!good_rows.contains(row)) {
                                    good_rows.add(row);
                                }
                            }
                        });

                        System.out.println("Finished checking rows");
                        scanner.close();
                        writer.println("FirstName;Surname;MobilePhone;Email;IDNumber;CustomVar1");
                        good_rows.forEach(row -> {
                            writer.println(row.get("FirstName") + ";"+ row.get("Surname") + ";" + row.get("MobilePhone") + ";" + row.get("Email") + ";" + row.get("IDNumber") + ";" + row.get("CustomVar1"));
                            writer.flush();
                        });

                        writer.flush();
                        writer.close();
                        in_new_file.close();
                        fixFileJob.setBad_rows_count(bad_rows.size());
                        fixFileJobQueueRepository.saveAndFlush(fixFileJob);

                        if(good_rows.size() > uploads_left) {
                            fixFileJob.setOver_limit(true);
                            fixFileJob.setStatus("error");
                            fixFileJobQueueRepository.saveAndFlush(fixFileJob);
                            File fixed_file = new File("./src/main/resources/temp/fixed_files/" + fixFileJob.getFile_uuid() + ".csv");
                            fixed_file.delete();

                        } else {
                            fixFileJob.setStatus("complete");
                            fixFileJobQueueRepository.saveAndFlush(fixFileJob);

                            upload_s3client.putObject("meetpat.fileuploads", "fixed_files/" + fixFileJob.getFile_uuid() + ".csv",
                                    new File("./src/main/resources/temp/fixed_files/" + fixFileJob.getFile_uuid() + ".csv"));
                        }

                    } else {
                        fixFileJob.setMatches_template(false);
                        fixFileJob.setStatus("error");
                        fixFileJobQueueRepository.saveAndFlush(fixFileJob);

                        File fixed_file = new File("./src/main/resources/temp/fixed_files/" + fixFileJob.getFile_uuid() + ".csv");
                        fixed_file.delete();

                    }

                } else {
                    fixFileJob.setValid_csv(false);
                    fixFileJob.setStatus("error");
                    fixFileJobQueueRepository.saveAndFlush(fixFileJob);
                    File fixed_file = new File("./src/main/resources/temp/fixed_files/" + fixFileJob.getFile_uuid() + ".csv");
                    fixed_file.delete();
                }

                File fixed_file = new File("./src/main/resources/temp/fixed_files/" + fixFileJob.getFile_uuid() + ".csv");
                File new_file = new File("./src/main/resources/temp/new_files/" + fixFileJob.getFile_uuid() + ".csv");

                fixed_file.delete();
                new_file.delete();
                upload_s3client.deleteObject("meetpat.fileuploads", "new_files/" + fixFileJob.getFile_uuid() + ".csv");

            } catch (IOException e) {
                fixFileJob.setStatus("error");
                fixFileJobQueueRepository.saveAndFlush(fixFileJob);
                File fixed_file = new File("./src/main/resources/temp/fixed_files/" + fixFileJob.getFile_uuid() + ".csv");
                File new_file = new File("./src/main/resources/temp/new_files/" + fixFileJob.getFile_uuid() + ".csv");

                fixed_file.delete();
                new_file.delete();
                upload_s3client.deleteObject("meetpat.fileuploads", "new_files/" + fixFileJob.getFile_uuid() + ".csv");

                e.printStackTrace();
            }

        }
    }