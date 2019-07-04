<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
ini_set('memory_limit', '-1');

class ApiController extends Controller
{
    //
    public function file_ready(Request $request)
    {
        if($request->output_file_name)
        {
            if(env('APP_ENV') == 'production')
            {
                $file_exists = \Storage::disk('sftp')->exists('Output/' . $request->output_file_name . '.csv');

            } else {
                $file_exists = \Storage::disk('local')->exists('Output/' . $request->output_file_name . '.csv');

            }

            if($file_exists)
            {
                if(env('APP_ENV') == 'production')
                {
                    $actual_file = \Storage::disk('sftp')->get('Output/' . $request->output_file_name . '.csv');
                } else {
                    $actual_file = \Storage::disk('local')->get('Output/' . $request->output_file_name . '.csv');
                }
                
                /**
                 * Use csv parser to get file data information.
                 */
                $csv = new \ParseCsv\Csv();
                $csv->delimiter = "|";
                $csv->parse($actual_file);
                // $parser = new \CsvParser\Parser('|', "'", "\n");
                // $file_data = $parser->fromString($actual_file);
                // $file_data_array = $parser->toArray($file_data);
                $file_data_array = $csv->data;
                // $array = array_map("str_getcsv", explode("\n", $actual_file));
                //          unset($array[0]);

                $filname_arr = explode('_', $request->output_file_name);

                if(count($filname_arr) !== 3)
                {
                    return response()->json(array("status" => "fail", "message" => "The file has been named incorrectly."), 400); 
                } 

                $audience_file = \MeetPAT\AudienceFile::where('file_unique_name', $filname_arr[count($filname_arr)-2])->first();

                if($audience_file)
                {
                    $barker_stree_file = \MeetPAT\BarkerStreetFile::create(array(
                     "file_unique_name" => $request->output_file_name,                     
                     "audience_file_id" => $audience_file->id,
                     "job_status" => "pending",
                     "user_id" => $audience_file->user_id,
                     "records" => sizeof($file_data_array)
                     )
                    );

                } else {
                    return response()->json(array("status" => "fail", "message" => "reference to file could not be found on server."), 400);
                }

            } else {
                return response()->json(array("status" => "fail", "message" => "file could not be found on the file server."), 400);
            }


        } else {
            return response()->json(array("status" => "fail", "message" => "output_file_name has not been provided."), 400);
        }
        \MeetPAT\Jobs\ProcessFile::dispatch();
        return response()->json(array("status" => "success", "message" => "File ready and queued for processing."), 200);
    }
}
