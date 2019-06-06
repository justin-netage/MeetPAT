<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function file_ready(Request $request)
    {
        if($request->client_file_name)
        {
            $file_exists = \Storage::disk('sftp')->exists('Archive/' . $request->client_file_name . '.csv');

            if($file_exists)
            {
                $actual_file = \Storage::disk('sftp')->get('Archive/' . $request->client_file_name . '.csv');

                $array = array_map("str_getcsv", explode("\n", $actual_file));
                         unset($array[0]);
                         unset($array[sizeof($array)]);

                $filname_arr = explode('_', $request->client_file_name);

                $audience_file = \MeetPAT\AudienceFile::where('file_unique_name', end($filname_arr))->first();

                if($audience_file)
                {
                    $barker_stree_file = \MeetPAT\BarkerStreetFile::create(array(
                     "file_unique_name" => $audience_file->file_unique_name,                     
                     "audience_file_id" => $audience_file->id,
                     "job_status" => "pending",
                     "user_id" => $audience_file->user_id,
                     "records" => sizeof($array)
                     )
                    );

                } else {
                    return response()->json(array("status" => "fail", "message" => "reference to file could not be found on server."), 418);
                }

            } else {
                return response()->json(array("status" => "fail", "message" => "file could not be found on the file server."), 400);
            }


        } else {
            return response()->json(array("status" => "fail", "message" => "client_file_name has not been provided."), 400);
        }



        return response()->json(array("status" => "success", "message" => "File ready and qued for processing."), 200);
    }
}
