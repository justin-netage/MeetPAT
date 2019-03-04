<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class DataVisualisationController extends Controller
{
    //

    public function index()
    {
        $user = \Auth::user();
        $audience_file = \MeetPAT\AudienceFile::where('user_id', $user->id)->first();

        return view('client.data_visualisation.records', ['file_id' => $audience_file->file_unique_name]);
    }

    public function large_data_upload_form()
    {
        return view('large_data_upload_form');
    }

    public function large_data_upload(Request $request)
    {
        
        $actual_file = null;

        if(env('APP_ENV') == 'production') {
            $actual_file = \Storage::disk('s3')->get('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id  . ".csv");
        } else {
            $actual_file = \Storage::disk('local')->get('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id  . ".csv");
        }

        $array = array_map("str_getcsv", explode("\n", $actual_file));
        unset($array[0]);
        unset($array[sizeof($array)]);

        if($actual_file) {
       
            $audience_file = \MeetPAT\AudienceFile::create(['user_id' => $request->user_id, 'audience_name' => $request->audience_name . " - " . time(), 'file_unique_name' => $request->file_id, 'file_source_origin' => $request->file_source_origin]);
            $created_job_que = \MeetPAT\RecordsJobQue::create(
                ['user_id' => $request->user_id, 'audience_file_id' => $audience_file->id, 'status' => 'pending', 'records' => sizeof($array), 'records_completed' => 0]
            );

        } else {
            return response("file does not exist :(");
        }
        
        return response()->json($created_job_que);
    }

    public function handle_upload(Request $request)
    {
        $csv_file = $request->file('audience_file');
        $fileName = uniqid();
        $path = $_FILES['audience_file']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_content = file_get_contents($csv_file);
           
        if($ext == 'csv') {
  

            if(env('APP_ENV') == 'production')
            {
                $directory_used = \Storage::disk('s3')->makeDirectory('client/client-records/');
                $file_uploaded = \Storage::disk('s3')->put('client/client-records/user_id_' . $request->user_id . '/' . $fileName  . ".csv", fopen($csv_file, 'r+'));
    
            } else {
                $directory_used = \Storage::disk('local')->makeDirectory('client/client-records/');
                $file_uploaded = \Storage::disk('local')->put('client/client-records/user_id_' . $request->user_id . '/' . $fileName  . ".csv", fopen($csv_file, 'r+'));
            }

        } else {
            return response()->json(["status" => 500, "error" => "Invalid CSV File"]);
        }

 
        
        return response()->json(["status" => 200,"file_id" => $fileName]);

    }
    public function handle_delete_upload(Request $request)
    {
        $file_exists = null;

        if(env('APP_ENV') == 'production') {
            $file_exists = \Storage::disk('s3')->exists('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        } else {
            $file_exists = \Storage::disk('local')->exists('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        }

        if($file_exists) {
            if(env('APP_ENV') == 'production') {
                $file_exists = \Storage::disk('s3')->delete('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
            } else {
                $file_exists = \Storage::disk('local')->delete('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
            }
        } else {
            return response(500);
        }

        // return response('File: '. $request->file_id .' -> has been removed');
        return response(200);

    }

    public function get_records(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::whereRaw("find_in_set('".$request->user_id."',affiliated_users)")->get();
        // $actual_file = null;

        // if(env('APP_ENV') == 'production') {
        //     $actual_file = \Storage::disk('s3')->get('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id  . ".csv");
        // } else {
        //     $actual_file = \Storage::disk('local')->get('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id  . ".csv");
        // }

        // $array = array_map("str_getcsv", explode("\n", $actual_file));
        // unset($array[0]);
        // unset($array[sizeof($array)]);

        // if($actual_file) {
       
        //     $audience_file = \MeetPAT\AudienceFile::where('user_id', $request->user_id)->first();
  
        // } else {
        //     return response("file does not exist :(");
        // }

        // $citizen = 0;
        // $resident = 0;
        // $baby_boomer_generation = 0;
        // $generation_x = 0;
        // $xennials_generation = 0;
        // $millennials_generation = 0;
        // $i_gen = 0;

        // foreach ($array as $h) {
    
        //     if($h[0]) {
        //         $citizen++;
        //     }

        //     if($h[25] == "True") {
        //         $resident++;
        //     }

        //     $year = substr($h[0], 0, 2);

        //     if($year) {
        //         if($year >= 46 and $year <= 64) {
        //             $baby_boomer_generation++;
        //         } else if($year >= 65 and $year <= 79) {
        //             $generation_x++;
        //         } else if($year >= 75 and $year <= 85) {
        //             $xennials_generation++;
        //         } else if($year >= 80 and $year <= 94) {
        //             $millennials_generation++;
        //         } else if($year >= 95 and $year <= 12) {
        //             $i_gen++;
        //         } 
        //     }

        // }

        // $generation = ["Baby Boomer" => $baby_boomer_generation, "Generation X" => $generation_x, "Xennials" => $xennials_generation, "Millennials" => $millennials_generation, "iGen" => $i_gen];

        
        $provinces = array_count_values(array_column($array, 'Province'));
        // $municipalities = array_count_values(array_column($array, 27));
        // $ages = array_count_values(array_column($array, 12));
        // $genders = array_count_values(array_column($array, 13));
        // $population_groups = array_count_values(array_column($array, 14));
        // $marital_statuses = array_count_values(array_column($array, 16));
        // $home_owner = array_count_values(array_column($array, 18));
        // $risk_category = array_count_values(array_column($array, 22));

        // return response()->json([ "contacts" => sizeof($array),
        //                           "provinces" => $provinces,
        //                           "municipality" => $municipalities,
        //                           "ages" => $ages,
        //                           "genders" => $genders,
        //                           "population_groups" => $population_groups,
        //                           "citizens_vs_residents" => [ $resident, $citizen ],
        //                           "marital_statuses" => $marital_statuses,
        //                           "generation" => $generation,
        //                           "home_owner" => $home_owner,
        //                           "risk_categories" => $risk_category
        //                         ]);

        return response()->json($records);
    }

    public function get_provinces(Request $request) {

        return response()->json($results);

    }

    public function get_municipalities(Request $request) {

        return response()->json($results);

    }

    public function get_ages(Request $request) {

        return response()->json($results);

    }

    public function get_genders(Request $request) {

        return response()->json($results);

    }

    public function get_population_groups(Request $request) {

        return response()->json($results);

    }

    public function get_marital_statuses(Request $request) {

        return response()->json($results);

    }

    public function get_home_owner(Request $request) {

        return response()->json($results);

    }

    public function get_risk_category(Request $request) {

        return response()->json($results);

    }

}
