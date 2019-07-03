<?php

namespace MeetPAT\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessFile implements ShouldQueue
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

    // Formatters

        // get age group ( AgeGroup ) from ex. 03. Thirties 
  
        protected function get_age_group($age_group) {
  
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
        protected function get_gender($gender)
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

        protected function get_population_group($p_group) 
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

        protected function get_income_bucket($income)
        {
            if($income)
            {
                return trim(explode('.', $income)[1]);
            } else {
                return "Unknown";
            }
        }

        // find CreditRiskCategory
        protected function find_category($category)
        {
            if($category)
            {
                return str_replace(" ", "_", trim(explode('.', $category)[1]));
            } else {
                return "Unkown";
            }
           
        }

        // format province
        protected function format_province($province)
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

        protected function get_generation($id_number) {
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

        protected function get_contact_category($contact_category)
        {
            if($contact_category)
            {
                return trim(explode('.', $contact_category)[1]);
            } else {
                return "Unknown";
            }
        }

        protected function get_valuation_bucket($property_value)
        {
            if($property_value)
            {
                switch ($property_value) {
                    case ($property_value > 0 and $property_value <= 1000000):
                        return 'R0 - R1 000 000';
                        break;
                    case ($property_value > 1000000 and $property_value <= 2000000):
                        return 'R1 000 000 - R2 000 000';
                        break;
                    case ($property_value > 2000000 and $property_value <= 4000000):
                        return 'R2 000 000 - R4 000 000';
                        break;
                    case ($property_value > 2000000 and $property_value <= 4000000):
                        return 'R4 000 000 - R6 000 000';
                        break;
                    case ($property_value >= 7000000):
                        return 'R7 000 000+';
                        break;
                    default:
                        return NULL;
                        break;
                }
            } else {
                return NULL;
            }
            
        }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $bsa_running_jobs = \MeetPAT\BarkerStreetFile::where('job_status', 'running')->count();
        $bsa_file_job = \MeetPAT\BarkerStreetFile::where('job_status', 'pending')->first();

        if($bsa_file_job)
        {
            $bsa_file_job->update(['job_status' => 'running']);
            $job_file = \MeetPAT\RecordsJobQue::where('audience_file_id', $bsa_file_job->audience_file_id)->first();
            $client_uploads = \MeetPAT\ClientUploads::where('user_id', $bsa_file_job->user_id)->first();
            $job_file->update(['records' => $bsa_file_job->records, 'records_checked' => $bsa_file_job->records]);

            if(!$client_uploads)
            {
                $client_uploads = \MeetPAT\ClientUploads::create(['user_id' => $bsa_file_job->user_id, 'uploads' => 0, 'upload_limit' => 10000]);
            }

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
                    $store_file = \Storage::disk('s3')->put('enriched-data/' . $bsa_file_job->file_unique_name . '.csv', $output_file);
                } else {
                    $output_file = \Storage::disk('local')->get('Output/' . $bsa_file_job->file_unique_name . '.csv');
                    $store_file = \Storage::disk('local')->put('enriched-data/' . $bsa_file_job->file_unique_name . '.csv', $output_file);

                }
    
                $parser = new \CsvParser\Parser('|', "'", "\n");
                $csv = $parser->fromString($output_file);
                $chunks = $parser->toChunks($csv, 1000);
    
                foreach($chunks as $chunk)
                {
                    $chunk->mapRows(function ($row) use ($bsa_file_job, $job_file, $client_uploads) {
                        \MeetPAT\EnrichedRecord::create(
                            array(
                                'RecordKey' => $row['RecordKey'],
                                'ClientFileName' => $row['ClientFileName'],
                                'ClientRecordID' => $row['ClientRecordID'],
                                'id6' => $row['id6'],
                                'FirstName' => encrypt($row['Firstname']),
                                'Middlename' => encrypt($row['Middlename']),
                                'Surname' => encrypt($row['Surname']),
                                'CleanPhone' => encrypt($row['CleanPhone']),
                                'Email1' => encrypt($row['Email1']),
                                'Email2' => encrypt($row['Email2']),
                                'Email3' => encrypt($row['Email3']),
                                'MobilePhone1' => encrypt($row['MobilePhone1']),
                                'MobilePhone2' => encrypt($row['MobilePhone2']),
                                'MobilePhone3' => encrypt($row['MobilePhone3']),
                                'WorkPhone1' => encrypt($row['WorkPhone1']),
                                'WorkPhone2' => encrypt($row['WorkPhone2']),
                                'WorkPhone3' => encrypt($row['WorkPhone3']),
                                'HomePhone1' => encrypt($row['HomePhone1']),
                                'HomePhone2' => encrypt($row['HomePhone2']),
                                'HomePhone3' => encrypt($row['HomePhone3']),
                                'ContactCategory' => $this->get_contact_category($row['ContactCategory']),
                                'AgeGroup' => $this->get_age_group($row['AgeGroup']),
                                'Gender' => $this->get_gender($row['Gender']),
                                'PopulationGroup' => $this->get_population_group($row['PopulationGroup']),
                                'DeceasedStatus' => $row['DeceasedStatus'],
                                'Generation' => $this->get_generation($row['id6']),
                                'MaritalStatus' => $row['MaritalStatus'],
                                'DirectorshipStatus' => $row['DirectorshipStatus'],
                                'HomeOwnershipStatus' => $row['HomeOwnershipStatus'],
                                'PrimaryPropertyType' => $row['PrimaryPropertyType'],
                                'PropertyValuation' => $row['PropertyValuation'],
                                'PropertyValuationBucket' => $this->get_valuation_bucket($row['PropertyValuation']),
                                'PropertyCount' => $row['PropertyCount'],
                                'Income' => $row['Income'],
                                'CreditRiskCategory' => $this->find_category($row['CreditRiskCategory']),
                                'IncomeBucket' => $this->get_income_bucket($row['IncomeBucket']),
                                'LSMGroup' => $row['LSMGroup'],
                                'HasResidentialAddress' => $row['HasResidentialAddress'],
                                'Province' => $this->format_province($row['Province']),
                                'Area' => $row['Area'],
                                'Municipality' => $row['Municipality'],
                                'Employer' => $row['Employer'],
                                'VehicleOwnershipStatus' => $row['VehicleOwnershipStatus'],
                                'InputIdn' => encrypt($row['InputIdn']),
                                'InputFirstName' => encrypt($row['InputFirstName']),
                                'InputSurname' => encrypt($row['InputSurname']),
                                'InputPhone' => encrypt($row['InputPhone']),
                                'InputEmail' => encrypt($row['InputEmail']),
                                'affiliated_users' => $bsa_file_job->user_id,
                            )
                        );
    
                        $bsa_file_job->increment('records_completed', 1);
                        $job_file->increment('records_completed', 1);
                        $client_uploads->increment('uploads', 1);                            

                        });
                    
                }

                $bsa_file_job->update(['job_status' => 'complete']);
                $job_file->update(['status' => 'done']);
            } else {
                $this->info('Could not find the output file');

                $bsa_file_job->update(['job_status' => 'error']);
                $job_file->update(['status' => 'done']);
            }
            
        } 
    }
}
