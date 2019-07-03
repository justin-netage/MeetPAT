<?php

namespace MeetPAT\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnrichRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $all_jobs = \MeetPAT\RecordsJobQue::all();
        $records_job_que = \MeetPAT\RecordsJobQue::where('status', 'pending')->get();
        $records_job_que_running = \MeetPAT\RecordsJobQue::where('status', 'running')->count();
        $insert_data = array(); // data to insert into the database after enriched
        $insert_data_first = array();
        
        // Change status of complete jobs.

        foreach($all_jobs as $job) {
            if($job->status == 'pending' or $job->status == 'running') {
                if($job->records_completed == $job->records) {
                    $job->update(['status' => 'done']);
                }
            }
        }

        foreach($records_job_que as $job) {

            if($job->status == 'pending') {
                $job->update(['status' => 'running']);

                $audience_file = \MeetPAT\AudienceFile::find($job->audience_file_id);
                $file_exists = '';

                if(env('APP_ENV') == 'production') {

                    $file_exists = \Storage::disk('s3')->exists('client/client-records/user_id_' . $audience_file->user_id . '/' . $audience_file->file_unique_name . ".csv");
                } else {
                    $file_exists = \Storage::disk('local')->exists('client/client-records/user_id_' . $audience_file->user_id . '/' . $audience_file->file_unique_name . ".csv");

                }

                if($file_exists) {
                    if(env('APP_ENV') == 'production') {
                        $actual_file = \Storage::disk('s3')->get('client/client-records/user_id_' . $audience_file->user_id . '/' . $audience_file->file_unique_name  . ".csv");
                    } else {
                        $actual_file = \Storage::disk('local')->get('client/client-records/user_id_' . $audience_file->user_id . '/' . $audience_file->file_unique_name  . ".csv");
                    }

                    $csv_parser = new \CsvParser\Parser(',', '"', "\n");
                    $csv_obj = $csv_parser->fromString($actual_file);
                    // $csv_obj->removeDuplicates('Email');
                    // $csv_obj->removeDuplicates('MobilePhone');
                    
                    // $array = array_map("str_getcsv", explode("\n", $actual_file));
                    // unset($array[0]);
                    // unset($array[sizeof($array)]);
                    // Remove duplicates in upload file. Check By Idn (ID Number)
                    // $tempArr = array_unique(array_column($array, 3));
                    // $records_array = array_intersect_key($array, $tempArr);
                    // $insert_data_first = collect($records_array);
                    // $data_chunks = $insert_data_first->chunk(1000);
                    $all_records = \MeetPAT\EnrichedRecord::all();
                    $data_to_enrich = array();
                    $data_chunks = $csv_parser->toChunks($csv_obj, 1000);
                    foreach($data_chunks as $data_chunk) {     
                        
                        foreach($csv_parser->toArray($data_chunk) as $row) {
                            $exists_email1 = $all_records->filter(function($record) use($row) {
                                if(decrypt($record["Email1"]) == $row["Email1"])
                                {
                                    return $record;
                                }
                            })->first();

                            $exists_email2 = $all_records->filter(function($record) use($row) {
                                if(decrypt($record["Email2"]) == $row["Email2"])
                                {
                                    return $record;
                                }
                            })->first();

                            $exists_email3 = $all_records->filter(function($record) use($row) {
                                if(decrypt($record["Email3"]) == $row["Email3"])
                                {
                                    return $record;
                                }
                            })->first();

                            $exists_phone1 = $all_records->filter(function($record) use($row) {
                                if(decrypt($record["MobilePhone1"]) == $row["MobilePhone1"])
                                {
                                    return $record;
                                }
                            })->first();

                            $exists_phone2 = $all_records->filter(function($record) use($row) {
                                if(decrypt($record["MobilePhone2"]) == $row["MobilePhone2"])
                                {
                                    return $record;
                                }
                            })->first();

                            $exists_phone3 = $all_records->filter(function($record) use($row) {
                                if(decrypt($record["MobilePhone3"]) == $row["MobilePhone3"])
                                {
                                    return $record;
                                }
                            })->first();

                            // $this->info('Client: ' . $client_already_exists . '(already exists)');
                                if($exists_email1 or $exists_email2 or $exists_email3 or $exists_phone1 or $exists_phone2 or $exists_phone3) {

                                if($exists_email1) {
                                    if(!in_array($audience_file->user_id, explode(",", $exists_email1->affiliated_users))) {
                                        $exists_email1->update(['affiliated_users' => $exists_email1->affiliated_users .',' . $audience_file->user_id]);
                                        }
                                } else if($exists_email2) {
                                    if(!in_array($audience_file->user_id, explode(",", $exists_email2->affiliated_users))) {
                                        $exists_email2->update(['affiliated_users' => $exists_email2->affiliated_users .',' . $audience_file->user_id]);
                                        }
                                } else if($exists_email3) {
                                    if(!in_array($audience_file->user_id, explode(",", $exists_email3->affiliated_users))) {
                                        $exists_email3->update(['affiliated_users' => $exists_email3->affiliated_users .',' . $audience_file->user_id]);
                                        }
                                } else if($exists_phone1) {
                                    if(!in_array($audience_file->user_id, explode(",", $exists_phone1->affiliated_users))) {
                                        $exists_phone1->update(['affiliated_users' => $exists_phone1->affiliated_users .',' . $audience_file->user_id]);
                                        }
                                } else if($exists_phone2) {
                                    if(!in_array($audience_file->user_id, explode(",", $exists_phone2->affiliated_users))) {
                                        $exists_phone2->update(['affiliated_users' => $exists_phone2->affiliated_users .',' . $audience_file->user_id]);
                                        }
                                } else if($exists_phone3) {
                                    if(!in_array($audience_file->user_id, explode(",", $exists_phone3->affiliated_users))) {
                                        $exists_phone3->update(['affiliated_users' => $exists_phone3->affiliated_users .',' . $audience_file->user_id]);
                                        }
                                }

                                // Not Used.
                                // $data = [
                                //     "InputIdn" => "",
                                //     "InputSurname" => check_value($row[1]),
                                //     "InputFirstName" => check_value($row[0]),
                                //     "InputPhone" => check_value(validate_mobile_number($row[2])),
                                //     "InputEmail" => check_value(validate_email_address($row[3]))
                                // ];

                                // $insert_data[] = $data;

                                $job->increment('records_checked', 1);

                                } else {
                                    $new_data = [
                                        "ClientFileName" => "meetpat_" . $audience_file->file_unique_name .".csv",
                                        "ClientRecordID" => $audience_file->file_unique_name,
                                        "InputIdn" => $row['IDNumber'],
                                        "InputFirstName" => $row['FirstName'],
                                        "InputSurname" => $row['Surname'],
                                        "InputPhone" => $row['MobilePhone'],
                                        "InputEmail" => $row['Email']
                                    ];

                                    $data_to_enrich[] = $new_data;
                                    $job->increment('records_checked', 1);

                                }
                        }

                }
                array_unshift($data_to_enrich, array("ClientFileName", "ClientRecordID", "InputIdn", "InputFirstName", "InputSurname", "InputPhone", "InputEmail"));

                $parser = new \CsvParser\Parser('|', '', "\n");
                $csv = $parser->fromArray($data_to_enrich);
                
                if(env('APP_ENV') == 'production')
                {
                    \Storage::disk('s3')->put('barker-street/meetpat_'.$audience_file->file_unique_name.'.csv', ltrim($parser->toString($csv),"0|1|2|3|4|5|6\n"), 'private');
                } else {
                    \Storage::disk('local')->put('barker-street/meetpat_'.$audience_file->file_unique_name.'.csv', ltrim($parser->toString($csv),"0|1|2|3|4|5|6\n"), 'private');
                }

                if(env('APP_ENV') == 'production')
                {
                    $uploaded_file = \Storage::disk('sftp')->put('Input/meetpat_'.$audience_file->file_unique_name.'.csv', ltrim($parser->toString($csv),"0|1|2|3|4|5|6\n"), 'public');

                } else {
                    $uploaded_file = \Storage::disk('local')->put('Input/meetpat_'.$audience_file->file_unique_name.'.csv', ltrim($parser->toString($csv),"0|1|2|3|4|5|6\n"), 'public');

                }                   
                    
                }
            }

        }

    }
}
