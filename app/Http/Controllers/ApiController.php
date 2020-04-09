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
            $file_exists = \Storage::disk('bsa_s3')->exists('ready/' . $request->output_file_name . '.csv');

            if($file_exists)
            {

                $filname_arr = explode('_', $request->output_file_name);

                if(count($filname_arr) !== 3)
                {
                    return response()->json(array("status" => "fail", "message" => "The file has been named incorrectly."), 400); 
                } 

                $audience_file = \MeetPAT\AudienceFile::where('file_unique_name', $filname_arr[count($filname_arr)-2])->first();

                $job_exists = \MeetPAT\BarkerStreetFile::where('audience_file_id', $audience_file->id)->first();

                if($audience_file and !$job_exists)
                {
                    $barker_stree_file = \MeetPAT\BarkerStreetFile::create(array(
                     "file_unique_name" => $request->output_file_name,                     
                     "audience_file_id" => $audience_file->id,
                     "job_status" => "pending",
                     "user_id" => $audience_file->user_id,
                     //"records" => sizeof($file_data_array)
                     )
                    );
                } else if($job_exists) {
                    return response()->json(array("status" => "fail", "message" => "found reference file on server already. Job already completed."), 400);
                } else if(!$audience_file) {
                    return response()->json(array("status" => "fail", "message" => "reference to file could not be found on server."), 400);
                }

            } else {
                return response()->json(array("status" => "fail", "message" => "file could not be found on the file server."), 400);
            }


        } else {
            return response()->json(array("status" => "fail", "message" => "output_file_name has not been provided."), 400);
        }
        
        //\MeetPAT\Jobs\ProcessFile::dispatch();
        return response()->json(array("status" => "success", "message" => "File ready and queued for processing."), 200);
    }

    public function get_aws_credentials(Request $request) {

        $user = \MeetPAT\User::where("api_token", $request->api_token)->get();

        if($user)
        {
            return response()->json(array(
                "SECRET_KEY" => env("UPLOAD_SECRET_KEY"),
                "ACCESS_ID" => env("UPLOAD_ACCESS_ID"),
            ), 200);
        } else {
            return response()->json(array("message" => "Unauthorized"), 401);
        }

        return response()->json(array("message" => "bad request"), 400);
    }
}
