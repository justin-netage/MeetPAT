<?php

namespace MeetPAT\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

ini_set('memory_limit', '512M');

class UploadClientRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'records:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uploads client records from file.';

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
        // Methods
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
                
                return $number;
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

        function find_income_bucket($income)
        {
            if($income < 2501) {
                return "R0 - R2 500";
            } else if ($income >= 2500 and $income < 5001) {
                return "R2 500 - R5 000";
            } else if ($income >= 5000 and  $income < 10001) {
                return "R5 000 - R10 000";
            } else if ($income >= 10000 and $income < 20001) {
                return "R10 000 - R20 000";
            } else if ($income >= 20000 and $income < 30001) {
                return "R20 000 - R30 000";
            } else if ($income >= 30000 and $income < 40001) {
                return "R30 000 - R40 000";
            } else {
                return "R40 000 +";
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

        $all_jobs = \MeetPAT\RecordsJobQue::all();
        $records_job_que = \MeetPAT\RecordsJobQue::where('status', 'pending')->get();
        $records_job_que_running = \MeetPAT\RecordsJobQue::where('status', 'running')->count();
        $insert_data = array();

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
                        $tempArr = array_unique(array_column($array, 0));
                        $records_array = array_intersect_key($array, $tempArr);
                        
                        foreach($records_array as $row) {      
                            $client_already_exists = \MeetPAT\BarkerStreetRecord::where('email', $row[3])->first();
                            $client_already_exists_phone = \MeetPAT\BarkerStreetRecord::where('MobilePhone1', $row[2])->first();
                            // $this->info('Client: ' . $client_already_exists . '(already exists)');
                             if(!$client_already_exists or !$client_already_exists_phone) {
                                 /* Using new data format
                                $data = [
                                    'Idn' => check_value($row[0]),
                                    'FirstName' => check_value($row[1]),
                                    'Surname' => check_value($row[2]),
                                    'MobilePhone1' => check_value(validate_mobile_number($row[3])),
                                    'MobilePhone2' => check_value(validate_mobile_number($row[4])),
                                    'MobilePhone3' => check_value(validate_mobile_number($row[5])),
                                    'WorkPhone1' => check_value(validate_mobile_number($row[6])),
                                    'WorkPhone2' => check_value(validate_mobile_number($row[7])),
                                    'WorkPhone3' => check_value(validate_mobile_number($row[8])),
                                    'HomePhone1' => check_value(validate_mobile_number($row[9])),
                                    'HomePhone2' => check_value(validate_mobile_number($row[10])),
                                    'HomePhone3' => check_value(validate_mobile_number($row[11])),
                                    'AgeGroup' => check_value(get_age_group($row[12])),
                                    'GenerationGroup' => check_value(get_generation($row[0])),
                                    'Gender' => check_value(get_gender($row[13])),
                                    'PopulationGroup' => check_value(get_population_group($row[14])),
                                    'DeceasedStatus' => check_value($row[15]),
                                    'MaritalStatus' => check_value($row[16]),
                                    'DirectorshipStatus' => check_value($row[17]),
                                    'HomeOwnerShipStatus' => check_value($row[18]),
                                    'income' => check_value($row[19]),
                                    'incomeBucket' => check_value(find_income_bucket($row[19])),
                                    'LSMGroup' => check_value($row[21]),
                                    'CreditRiskCategory' => check_value(find_category($row[22])),
                                    'ContactCategory' => check_value(find_category($row[23])),
                                    'HasMobilePhone' => check_value($row[24]),
                                    'HasResidentialAddress' => check_value($row[25]),
                                    'Province' => check_value(format_province($row[26])),
                                    'GreaterArea' => check_value($row[27]),
                                    'Area' => check_value($row[28]),
                                    'ResidentialAddress1Line1' => check_value($row[29]),
                                    'ResidentialAddress1Line2' => check_value($row[30]),
                                    'ResidentialAddress1Line3' => check_value($row[31]),
                                    'ResidentialAddress2Line4' => check_value($row[32]),
                                    'ResidentialAddress2PostalCode' => check_value($row[33]),
                                    'PostalAddress1Line1' => check_value($row[34]),
                                    'PostalAddress1Line2' => check_value($row[35]),
                                    'PostalAddress1Line3' => check_value($row[36]),
                                    'PostalAddress1Line4' => check_value($row[37]),
                                    'PostalAddress1PostalCode' => check_value($row[38]),
                                    'email' => check_value($row[39]),
                                    'affiliated_users' => $audience_file->user_id,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];
                                */

                                $data = [
                                    "FirstName" => check_value($row[0]),
                                    "Surname" => check_value($row[1]),
                                    "MobilePhone1" => check_value(validate_mobile_number($row[2])),
                                    "email" => check_value(validate_email_address($row[3]))
                                ];

                                $insert_data[] = $data;
                                $job->increment('records_checked', 1);

                             } else {
                                 if(!in_array($audience_file->user_id, explode(",", $client_already_exists->affiliated_users))) {
                                    $client_already_exists->update(['affiliated_users' => $client_already_exists->affiliated_users .',' . $audience_file->user_id]);
                                 }
                                 $job->increment('records_checked', 1);

                             }

                    }
                
                    $insert_data = collect($insert_data);
                    $job->update(['records' => sizeof($insert_data)]);
                    $chunks = $insert_data->chunk(1000);
                
                    foreach($chunks as $chunk) {
                        // \MeetPAT\BarkerStreetRecord::insert($chunk->toArray());
                        // New Handeling method for uploaded contacts

                        $job->increment('records_completed', sizeof($chunk));
                        
                    }
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
