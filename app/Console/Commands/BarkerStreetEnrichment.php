<?php

namespace MeetPAT\Console\Commands;

use Illuminate\Console\Command;

class BarkerStreetEnrichment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrich:records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enriching data with Barker Street Anylitics';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
          // Methods to validate data to submit
          function check_value($value)
          {
              if($value == '')
              {
                  return 'Unknown';
              } else {
                  return $value;
              }
          }
  
          function validate_email_address($value)
          {
              $pattern = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
              if(preg_match($pattern, $value)) {
                  return $value;
              } else {
                  return 'Unkown';
              }
          }
  
          // validate mobile numbers
  
          function validate_mobile_number($number) {
  
              if(strlen($number) == 11 and $number[0] == '2' and $number[1] == '7') {
                  
                  return '+' . $number;
              } else {
  
                  return 'Unknown';
  
              }
  
          }
  
          // get age group ( AgeGroup )from ex. 03. Thirties 
  
          function get_age_group($age_group) {
  
              switch ($age_group) {
                  case "02. Twenties":
                      return "Twenties";
                      break;
                  case "03. Thirties":
                      return "Thirties";
                      break;
                  case "04. Fourties":
                      return "Fourties";
                      break;
                  case "05. Fifties":
                      return "Fifties";
                      break;  
                   case "06. Sixties":
                      return "Sixties";
                      break;  
                  case "07. Senventies":
                      return "Senventies";
                      break;  
                  case "08. Eighty +":
                      return "Eighty +";
                      break;    
                  default:
                      return 'Unknown';                                                   
              }
  
  
          }
  
          // get Gender F , M
          function get_gender($gender)
          {
              switch ($gender) {
                  case "Male":
                      return "M";
                      break;
                  case "Female":
                      return "F";
                      break;
                  default:
                      return 'Unknown';
              }
          }
  
          // get PopulationGroup
  
          function get_population_group($p_group) 
          {
              switch ($p_group) {
                  case "Black":
                      return "B";
                      break;
                  case "White":
                      return "W";
                      break;
                  case "Coloured":
                      return "C";
                      break;
                  case "Asian":
                      return "A";
                      break;
                  case "Unknown":
                      return "Unknown";
                      break;
                  default:
                      return "Unknown";
              }
          }
  
          // find IncomeBucket
  
          function get_income_bucket($income)
          {
              switch ($income) {
                  case '01. R0 - R2 500':
                      return "R0 - R2 500";
                      break;
                  case '02. R2 500 - R5 000':
                      return "R2 500 - R5 000";
                      break;
                  case '03. R5 000 - R10 000':
                      return "R5 000 - R10 000";
                      break;
                  case '04. R10 000 - R20 000':
                      return "R10 000 - R20 000";
                      break;
                  case '05. R20 000 - R30 000':
                      return "R20 000 - R30 000";
                      break;
                  case '06. R30 000 - R40 000':
                      return "R30 000 - R40 000";
                      break;
                  case '07. R40 000 +':
                      return "R40 000 +";
                      break;
                  default:
                      return "Unknown";
                      break;
              }
          }
  
          // find CreditRiskCategory
          function find_category($category)
          {
              return str_replace(" ", "_", trim(explode('.', $category)[1]));
          }
  
          // format province
          function format_province($province)
          {
              switch ($province) {
                  case "Gauteng":
                      return "G";
                      break;
                  case "Eastern Cape":
                      return "EC";
                      break;
                  case "Western Cape":
                      return "WC";
                      break;
                  case "Northern Cape":
                      return "NC";
                  case "Limpopo":
                      return "L";
                      break;
                  case "Free State":
                      return "FS";
                      break;
                  case "Mpumalanga":
                      return "M";
                      break;
                  case "Kwazulu-Natal":
                      return "KN";
                      break;
                  case "North West":
                      return "NW";
                      break;
                  default:
                      return 'Unknown';
              }
          }
  
          function get_generation($id_number) {
              $year = substr($id_number, 0, 2);
              $generation;
              if($year) {
                  if($year >= 46 and $year <= 64) {
                      $generation = "Baby Boomer";
                  } else if($year >= 65 and $year <= 79) {
                      $generation = "Generation X";
                  } else if($year >= 75 and $year <= 85) {
                      $generation = "Xennials";
                  } else if($year >= 80 and $year <= 94) {
                      $generation = "Millennials";
                  } else if($year >= 95 and $year <= 12) {
                      $generation = "iGen";
                  } else {
                      $generation = "Unknown";
                  }
              }
  
              return $generation;
          }
        
        // Get jobs that are pending and run them if there aren't current jobs running 

        $all_jobs = \MeetPAT\RecordsJobQue::all();
        $records_job_que = \MeetPAT\RecordsJobQue::where('status', 'pending')->get();
        $records_job_que_running = \MeetPAT\RecordsJobQue::where('status', 'running')->count();
        $insert_data = array(); // data to insert into the database after enriched
        $insert_data_first = array();
        
        // Change status of complete jobs.
        function check_complete($jobs_array) {
            foreach($jobs_array as $job) {
                if($job->status == 'pending' or $job->status == 'running') {
                    if($job->records_completed == $job->records) {
                        $job->update(['status' => 'done']);
                    }
                }
            }
        }

        check_complete($all_jobs);

        if($records_job_que_running == 0) {
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
    
                        $array = array_map("str_getcsv", explode("\n", $actual_file));
                        unset($array[0]);
                        unset($array[sizeof($array)]);
                        // Remove duplicates in upload file. Check By Idn (ID Number)
                        $tempArr = array_unique(array_column($array, 3));
                        $records_array = array_intersect_key($array, $tempArr);
                        $insert_data_first = collect($records_array);
                        $data_chunks = $insert_data_first->chunk(1000);
                        $data_to_enrich = array();
                        
                        foreach($data_chunks as $data_chunk) {     
                            
                            foreach($data_chunk as $row) {
                                $client_already_exists_email = \MeetPAT\BarkerStreetEnrichedRecord::where('InputEmail', $row[3])->first();
                                $client_already_exists_phone = \MeetPAT\BarkerStreetEnrichedRecord::where('InputPhone', $row[2])->first();
                                // $this->info('Client: ' . $client_already_exists . '(already exists)');
                                 if($client_already_exists_email or $client_already_exists_phone) {
    
                                    if($client_already_exists_email) {
                                        if(!in_array($audience_file->user_id, explode(",", $client_already_exists_email->affiliated_users))) {
                                            $client_already_exists_email->update(['affiliated_users' => $client_already_exists_email->affiliated_users .',' . $audience_file->user_id]);
                                         }
                                    } else if($client_already_exists_phone) {
                                        if(!in_array($audience_file->user_id, explode(",", $client_already_exists_phone->affiliated_users))) {
                                            $client_already_exists_phone->update(['affiliated_users' => $client_already_exists_phone->affiliated_users .',' . $audience_file->user_id]);
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
                                         "ClientFileName" => "meetpatClientFile_" . $audience_file->file_unique_name .".csv",
                                         "ClientRecordID" => "",
                                         "InputIdn" => "",
                                         "InputFirstName" => check_value($row[0]),
                                         "InputSurname" => check_value($row[1]),
                                         "InputPhone" => check_value(validate_mobile_number($row[2])),
                                         "InputEmail" => check_value(validate_email_address($row[3]))
                                     ];

                                     $data_to_enrich[] = $new_data;
                                    //  $job->increment('records_checked', 1);
    
                                 }
                            }

                    }
                    array_unshift($data_to_enrich, array("ClientFileName", "ClientRecordID", "InputIdn", "InputFirstName", "InputSurname", "InputPhone", "InputEmail"));

                    $parser = new \CsvParser\Parser('|', '', "\n");
                    $csv = $parser->fromArray($data_to_enrich);

                    if(env('APP_ENV') == 'production')
                    {
                        \Storage::disk('s3')->put('barker-street/meetpatClientFile_'.$audience_file->file_unique_name.'.csv', ltrim($parser->toString($csv),"0|1|2|3|4|5|6\n"), 'private');
                    } else {
                        \Storage::disk('local')->put('barker-street/meetpatClientFile_'.$audience_file->file_unique_name.'.csv', ltrim($parser->toString($csv),"0|1|2|3|4|5|6\n"), 'private');
                    }

                    

                    /** When the file is available from BSA run a separate scheduled task. */

                    // if($data_to_enrich) 
                    // {
                    //     $data_to_enrich = collect($data_to_enrich);
                    //     $job->update(['records' => sizeof($data_to_enrich)]);
                    //     $new_data_chunks = $data_to_enrich->chunk(1000);
                    //     $uploads = \MeetPAT\ClientUploads::where('user_id', $job->user_id)->first();
    
                    //     if(!$uploads)
                    //     {
                    //         $uploads = \MeetPAT\ClientUploads::create(['user_id' => $job->user_id, 'uploads' => 0, 'upload_limit' => 10000]);
                    //     }
    
                    //     foreach($new_data_chunks as $new_data_chunk) {
                    //         // \MeetPAT\BarkerStreetRecord::insert($new_data_chunk->toArray());
                    //         // New Handeling method for uploaded contacts
    
                            
    
                    //     }
                    // } else {
                        
                    // }

                    
                        check_complete($all_jobs);
                    }
                }

                $job->update(['status' => 'done']);

            }

        } else {
            $this->info('Jobs already running.');
        }
    }
}
