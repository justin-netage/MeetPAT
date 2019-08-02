<?php

namespace MeetPAT\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

ini_set('memory_limit', '-1');

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
        
        // Change status of complete jobs.

        foreach($all_jobs as $job) {
            if($job->status == 'pending' or $job->status == 'running') {
                if($job->records_completed == $job->records) {
                    $job->update(['status' => 'done']);
                }
            }
        }

        $job_pending = \MeetPAT\RecordsJobQue::where("status", "pending")->first();

           
        $job_pending->update(['status' => 'running']);

        $audience_file = \MeetPAT\AudienceFile::find($job_pending->audience_file_id);
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

            $csv_p = new \ParseCsv\Csv();
            $csv_p->delimeter = ",";
            $csv_p->parse($actual_file);

            //$all_records = \MeetPAT\EnrichedRecord::select("Email1", "Email2", "Email3", "MobilePhone1","MobilePhone2","MobilePhone3");
            
            $data_chunks = array_chunk($csv_p->data, 1000);
            $update_array = [];

            foreach($data_chunks as $data_chunk) {     
                
                foreach($data_chunk as $row) {
                    // Fuzzy Match using laravel searchy
                    $email_exists = \Searchy::search('enriched_records')->fields('Email1', 'Email2', 'Email3')->select('id', 'affiliated_users')->query($row['Email'])->having('relevance', '>', 200)->get()->toArray();
                    $phone_exists = \Searchy::search('enriched_records')->fields('MobilePhone1', 'MobilePhone1', 'MobilePhone1', 'CleanPhone')->select('id', 'affiliated_users')->query($row['MobilePhone'])->having('relevance', '>', 200)->get()->toArray();
                    
                    if($email_exists or $phone_exists) {

                        if($email_exists and !in_array(explode(",", $email_exists[0]->affiliated_users)))
                        {
                            array_push($update_array, $email_exists[0]->id);
                        } else if($phone_exists and !in_array(explode(",", $phone_exists[0]->affiliated_users))) {
                            array_push($update_array, $phone_exists[0]->id);
                        }

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

                        }
                }
                $job_pending->increment('records_checked', sizeof($data_chunk));

            }

            //Update all exising records with affiliated user records
            \MeetPAT\EnrichedRecord::select("affiliated_users")->whereIn("id", $update_array)->update(["affiliated_users" => \DB::raw('CONCAT(affiliated_users, " ,", ' . '"' . $audience_file->user_id . '")')]);

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
