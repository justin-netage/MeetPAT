<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

ini_set('memory_limit', '256M');


class DataVisualisationController extends Controller
{
    //

    public function index()
    {
        $user = \Auth::user();
        $records = \MeetPAT\BarkerStreetRecord::whereRaw("find_in_set('".$user->id."',affiliated_users)")->count();
        $user_jobs = \MeetPAT\RecordsJobQue::where('user_id', $user->id);
        $user_jobs_running = $user_jobs->where(function($q) {
            $q->where('status', 'pending')->orWhere('status', 'running');
        })->count();
        //$user_jobs_complete = \MeetPAT\RecordsJobQue::where(['user_id' => $user->id, 'status' => 'done'])->first();

        if($user_jobs_running) {
            return view('client.data_visualisation.records_updating');
        } else if($records) {
            return view('client.data_visualisation.records');
        } else {
            return view('client.data_visualisation.records_none');
        }
        
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

        return response(200);

    }

    public function get_records_count(Request $request)
    {
        $records_count = \MeetPAT\BarkerStreetRecord::whereRaw("find_in_set('".$request->user_id."',affiliated_users)");

        // Filter By Province
        if($request->selected_provinces) {
            $records_count = $records_count->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Group
        if($request->selected_age_groups) {
            $records_count = $records_count->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records_count = $records_count->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records_count = $records_count->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation
        if($request->selected_generations) {
            $records_count = $records_count->whereIn('GenerationGroup', $request->selected_generations);
        }

        return response($records_count->count());
    }

    public function get_municipalities(Request $request) {

        $records = \MeetPAT\BarkerStreetRecord::select('GreaterArea')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation Group
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();

        $municipalities = array_count_values(array_column($records->toArray(), 'GreaterArea'));
        arsort($municipalities);

        return response()->json($municipalities);

    }

    public function get_provinces(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::select('Province')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_provinces = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation Group
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();
        $provinces = array_count_values(array_column($records->toArray(), 'Province'));
        $all_provinces = array_count_values(array_column($all_provinces->toArray(), 'Province'));
        arsort($all_provinces);
        arsort($provinces);

        return response()->json(["selected_provinces" => $provinces, "all_provinces" => $all_provinces, "request_provinces" => $request->selected_provinces, 'request_ages' => $request->selected_age_groups]);
    }

    public function get_ages(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::select('AgeGroup')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_ages = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();
        $ages = array_count_values(array_column($records->toArray(), 'AgeGroup'));
        $all_ages = array_count_values(array_column($all_ages->toArray(), 'AgeGroup'));
        arsort($all_ages);
        arsort($ages);

        return response()->json(["selected_ages" => $ages, "all_ages" => $all_ages]);

    }

    public function get_genders(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::select('Gender')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_genders = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();
        $genders = array_count_values(array_column($records->toArray(), 'Gender'));
        $all_genders = array_count_values(array_column($all_genders->toArray(), 'Gender'));

        arsort($all_genders);
        arsort($genders);

        return response()->json(["selected_genders" => $genders, "all_genders" => $all_genders]);

    }

    public function get_population_groups(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::select('PopulationGroup')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_population_groups = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();
        $population_groups = array_count_values(array_column($records->toArray(), 'PopulationGroup'));
        $all_population_groups = array_count_values(array_column($all_population_groups->toArray(), 'PopulationGroup'));

        arsort($population_groups);
        arsort($all_population_groups);

        return response()->json(["selected_population_groups" => $population_groups, "all_population_groups" => $all_population_groups]);
    }

    public function get_home_owner(Request $request) 
    {
        $records = \MeetPAT\BarkerStreetRecord::select('HomeOwnerShipStatus')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();

        $home_owner = array_count_values(array_column($records->toArray(), 'HomeOwnerShipStatus'));
        arsort($home_owner);

        return response()->json($home_owner);

    }

    public function get_household_income(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::select('income', 'incomeBucket')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();
        $household_income = array_count_values(array_column($records->toArray(), 'incomeBucket'));
        arsort($household_income);

        return response()->json($household_income);
    }

    public function get_risk_category(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::select('CreditRiskCategory')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }        

        $records = $records->get();
        $risk_category = array_count_values(array_column($records->toArray(), 'CreditRiskCategory'));
        arsort($risk_category);

        return response()->json($risk_category);

    }

    public function get_director_of_business(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::select('DirectorshipStatus')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();
        $director_of_business = array_count_values(array_column($records->toArray(), 'DirectorshipStatus'));
        arsort($director_of_business);

        return response()->json($director_of_business);

    }


    public function get_citizens_and_residents(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::select('Idn', 'HasResidentialAddress')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
    
        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }
        
        $records = $records->get();

        $citizen = 0;
        $resident = 0;

        foreach ($records as $row) {
    
            if($row->Idn) {
                $citizen++;
            }

            if($row->HasResidentialAddress == "true") {
                $resident++;
            }

        }

        $citizens_and_residents = [ "Resident" => $resident, "Citizen" => $citizen ];
        arsort($citizens_and_residents);

        return response()->json($citizens_and_residents);
    }

    public function get_generations(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::select('GenerationGroup')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_generations = $records->get();
        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation Group
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();
        $generations = array_count_values(array_column($records->toArray(), 'GenerationGroup'));
        $all_generations = array_count_values(array_column($all_generations->toArray(), 'GenerationGroup'));

        arsort($generations);
        arsort($all_generations);

        return response()->json(["selected_generations" => $generations, "all_generations" => $all_generations]);
    }

    public function get_marital_statuses(Request $request)
    {
        $records = \MeetPAT\BarkerStreetRecord::select('MaritalStatus')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation Group
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();
        $marital_statuses = array_count_values(array_column($records->toArray(), 'MaritalStatus'));
        arsort($marital_statuses);

        return response()->json($marital_statuses);
    }

    public function get_area(Request $request) {

        $records = \MeetPAT\BarkerStreetRecord::select('Area')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        
        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Age Groups
        if($request->selected_age_groups) {
            $records = $records->whereIn('AgeGroup', $request->selected_age_groups);
        }
        // Filter By Gender
        if($request->selected_gender_groups) {
            $records = $records->whereIn('Gender', $request->selected_gender_groups);
        }
        // Filter By Population Group
        if($request->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', $request->selected_population_groups);
        }
        // Filter By Generation Group
        if($request->selected_generations) {
            $records = $records->whereIn('GenerationGroup', $request->selected_generations);
        }

        $records = $records->get();
        $areas = array_count_values(array_column($records->toArray(), 'Area'));

        arsort($areas);

        return response()->json($areas);
    }

    // Part of Api for Progress Tracking
    public function get_job_que(Request $request) {

        $jobs = \MeetPAT\RecordsJobQue::where('user_id', $request->user_id)->with('audience_file')->orderBy('created_at', 'DESC')->whereDate('created_at', '=', Carbon::today()->toDateString());
        $running_jobs = \MeetPAT\RecordsJobQue::where('user_id', $request->user_id)->where(function($q) {
            $q->where('status', 'pending')->orWhere('status', 'running');
        })->count();

        return response()->json(["jobs" => $jobs->get()->toArray(), "jobs_running" => $running_jobs]);
    
    }

}