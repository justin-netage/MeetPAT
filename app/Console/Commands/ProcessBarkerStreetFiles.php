<?php

namespace MeetPAT\Console\Commands;

use Illuminate\Console\Command;

class ProcessBarkerStreetFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processing Barker Street Anylytics Enriched file';

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
        // Hash
        function normalizeAndHash($value)
        {
            if($value)
            {
                return hash('sha256', strtolower(trim($value)));
            } else {
                return $value;
            }
            
        }

        // Formatters

        // get age group ( AgeGroup ) from ex. 03. Thirties 
  
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
            if($income)
            {
                return trim(explode('.', $income)[1]);
            } else {
                return "Unknown";
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
            
                if($year >= 46 and $year <= 64) {
                    return "Baby Boomer";
                } else if($year >= 65 and $year <= 79) {
                    return "Generation X";
                } else if($year >= 75 and $year <= 85) {
                    return"Xennials";
                } else if($year >= 80 and $year <= 94) {
                    return "Millennials";
                } else if($year >= 95) {
                    return "iGen";
                } else {
                    return "Unknown";
                }
            
        }

        function get_contact_category($contact_category)
        {
            if($contact_category)
            {
                return trim(explode('.', $contact_category)[1]);
            } else {
                return "Unknown";
            }
        }

        /**
         * Look for ready barker street files to run in que
         */

        // $jobs_que = \MeetPAT\
        $bsa_running_jobs = \MeetPAT\BarkerStreetFile::where('job_status', 'running')->count();
        $bsa_file_job = \MeetPAT\BarkerStreetFile::where('job_status', 'pending')->first();

        if($bsa_running_jobs == 0)
        {
            if($bsa_file_job)
            {
                $job_file = \MeetPAT\RecordsJobQue::where('audience_file_id', $bsa_file_job->audience_file_id)->first();

                if(env('APP_ENV') == 'production')
                {
                    $output_file_exists = \Storage::disk('sftp')->exists('Output/' . $bsa_file_job->file_unique_name . '.csv');
                } else {
                    $output_file_exists = \Storage::disk('local')->exists('Output/' . $bsa_file_job->file_unique_name . '.csv');
                }

                if($output_file_exists)
                {
                    if(env('APP_ENV') == 'production')
                    {
                        $output_file = \Storage::disk('sftp')->get('Output/' . $bsa_file_job->file_unique_name . '.csv');
                    } else {
                        $output_file = \Storage::disk('local')->get('Output/' . $bsa_file_job->file_unique_name . '.csv');
                    }
        
                    $parser = new \CsvParser\Parser('|', "'", "\n");
                    $csv = $parser->fromString($output_file);
                    $chunks = $parser->toChunks($csv, 100);
        
                    foreach($chunks as $chunk)
                    {
                        $chunk->mapRows(function ($row) use ($bsa_file_job, $job_file) {
                            \MeetPAT\EnrichedRecord::create(
                                array(
                                    'RecordKey' => $row['RecordKey'],
                                    'ClientFileName' => $row['ClientFileName'],
                                    'ClientRecordID' => $row['ClientRecordID'],
                                    'id6' => $row['id6'],
                                    'FirstName' => normalizeAndHash($row['Firstname']),
                                    'Middlename' => normalizeAndHash($row['Middlename']),
                                    'Surname' => normalizeAndHash($row['Surname']),
                                    'CleanPhone' => normalizeAndHash($row['CleanPhone']),
                                    'Email1' => normalizeAndHash($row['Email1']),
                                    'Email2' => normalizeAndHash($row['Email2']),
                                    'Email3' => normalizeAndHash($row['Email3']),
                                    'MobilePhone1' => normalizeAndHash($row['MobilePhone1']),
                                    'MobilePhone2' => normalizeAndHash($row['MobilePhone2']),
                                    'MobilePhone3' => normalizeAndHash($row['MobilePhone3']),
                                    'WorkPhone1' => normalizeAndHash($row['WorkPhone1']),
                                    'WorkPhone2' => normalizeAndHash($row['WorkPhone2']),
                                    'WorkPhone3' => normalizeAndHash($row['WorkPhone3']),
                                    'HomePhone1' => normalizeAndHash($row['HomePhone1']),
                                    'HomePhone2' => normalizeAndHash($row['HomePhone2']),
                                    'HomePhone3' => normalizeAndHash($row['HomePhone3']),
                                    'ContactCategory' => get_contact_category($row['ContactCategory']),
                                    'AgeGroup' => get_age_group($row['AgeGroup']),
                                    'Gender' => get_gender($row['Gender']),
                                    'PopulationGroup' => get_population_group($row['PopulationGroup']),
                                    'DeceasedStatus' => $row['DeceasedStatus'],
                                    'Generation' => get_generation($row['id6']),
                                    'MaritalStatus' => $row['MaritalStatus'],
                                    'DirectorshipStatus' => $row['DirectorshipStatus'],
                                    'HomeOwnershipStatus' => $row['HomeOwnershipStatus'],
                                    'PrimaryPropertyType' => $row['PrimaryPropertyType'],
                                    'PropertyValuation' => $row['PropertyValuation'],
                                    'PropertyCount' => $row['PropertyCount'],
                                    'Income' => $row['Income'],
                                    'IncomeBucket' => get_income_bucket($row['IncomeBucket']),
                                    'LSMGroup' => $row['LSMGroup'],
                                    'HasResidentialAddress' => $row['HasResidentialAddress'],
                                    'Province' => format_province($row['Province']),
                                    'Area' => $row['Area'],
                                    'Municipality' => $row['Municipality'],
                                    'Employer' => $row['Employer'],
                                    'VehicleOwnershipStatus' => $row['VehicleOwnershipStatus'],
                                    'InputIdn' => normalizeAndHash($row['InputIdn']),
                                    'InputFirstName' => normalizeAndHash($row['InputFirstName']),
                                    'InputSurname' => normalizeAndHash($row['InputSurname']),
                                    'InputPhone' => normalizeAndHash($row['InputPhone']),
                                    'InputEmail' => normalizeAndHash($row['InputEmail']),
                                    'affiliated_users' => $bsa_file_job->user_id,
                                )
                            );
        
                            $bsa_file_job->increment('records_completed', 1);
                            $job_file->increment('records_completed', 1);
    
                         });
                        
                    }
    
                    $bsa_file_job->update(['job_status' => 'complete']);
                    $job_file->update(['status' => 'done']);
                } 
                
            } else {
                $this->info('No jobs pending.');
            }

        } else {
            $this->info('A Job file is already being processed.');
        }

        
    
    }
}

