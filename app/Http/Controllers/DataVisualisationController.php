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
        $records = \MeetPAT\EnrichedRecord::whereRaw("find_in_set('".$user->id."',affiliated_users)")->count();
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
        $user = \Auth::user();
        $user_jobs = \MeetPAT\RecordsJobQue::where('user_id', $user->id);
        $user_jobs_running = $user_jobs->where(function($q) {
            $q->where('status', 'pending')->orWhere('status', 'running');
        })->count();
        if($user_jobs_running)
        {
            return redirect('meetpat-client/data-visualisation');
        } else {
            return view('large_data_upload_form');
        }
        
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
        \MeetPAT\Jobs\EnrichRecords::dispatch()->onQueue("enrich");
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
    /**
     * API Routes to get data to populate graph.
     */

    public function get_records_count(Request $request)
    {
        $records_count = \MeetPAT\EnrichedRecord::whereRaw("find_in_set('".$request->user_id."',affiliated_users)");

        // Filter By Province
        if($request->selected_provinces) {
            $records_count = $records_count->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records_count = $records_count->whereIn('Municipality', $request->selected_municipalities);
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
            $records_count = $records_count->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records_count = $records_count->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records_count = $records_count->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }        
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records_count = $records_count->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records_count = $records_count->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records_count = $records_count->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records_count = $records_count->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records_count = $records_count->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records_count = $records_count->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records_count = $records_count->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records_count = $records_count->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records_count = $records_count->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records_count = $records_count->where('id6', '!=', '');

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records_count = $records_count->where('HasResidentialAddress', "true");

            }
        }

        return response($records_count->count());
    }

    public function get_municipalities(Request $request) {

        // $records = \MeetPAT\BarkerStreetRecord::select('GreaterArea')->where([['PropertyValuationBucket', '!=', null]])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $records = \MeetPAT\EnrichedRecord::select('Municipality')->where([['Municipality', '!=', null], ['Municipality', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_municipalities = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        }
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
        }
        // Filter By Municipalities
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }   
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        } 
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }     
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }       
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();

        // $municipalities = array_count_values(array_column($records->toArray(), 'GreaterArea'));
        // $all_municipalities = array_count_values(array_column($records->toArray(), 'GreaterArea'));
        $municipalities = array_count_values(array_column($records->toArray(), 'Municipality'));
        $all_municipalities = array_count_values(array_column($records->toArray(), 'Municipality'));

        arsort($municipalities);
        arsort($all_municipalities);

        return response()->json(["selected_municipalities" => $municipalities, "all_municipalities" => $all_municipalities]);

    }

    public function get_provinces(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('Province')->where([['Province', '!=', null], ['Province', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_provinces = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();
        $provinces = array_count_values(array_column($records->toArray(), 'Province'));
        $all_provinces = array_count_values(array_column($all_provinces->toArray(), 'Province'));

        arsort($all_provinces);
        arsort($provinces);

        return response()->json(["selected_provinces" => $provinces, "all_provinces" => $all_provinces, "request_provinces" => $request->selected_provinces]);
    }

    public function get_ages(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('AgeGroup')->where([['AgeGroup', '!=', null], ['AgeGroup', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_ages = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
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
        $records = \MeetPAT\EnrichedRecord::select('Gender')->where([['Gender', '!=', null], ['Gender', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_genders = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
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
        $records = \MeetPAT\EnrichedRecord::select('PopulationGroup')->where([['PopulationGroup', '!=', null], ['PopulationGroup', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_population_groups = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }   
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
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
        $records = \MeetPAT\EnrichedRecord::select('HomeOwnershipStatus')->where([['HomeOwnershipStatus', '!=', null], ['HomeOwnershipStatus', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_home_owners = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();

        $home_owner = array_count_values(array_column($records->toArray(), 'HomeOwnershipStatus'));
        $all_home_owners = array_count_values(array_column($all_home_owners->toArray(), 'HomeOwnershipStatus'));
        arsort($home_owner);
        arsort($all_home_owners);

        return response()->json(["selected_home_owners" => $home_owner, "all_home_owners" => $all_home_owners]);

    }

    public function get_vechicle_owner(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('VehicleOwnershipStatus')->where([['VehicleOwnershipStatus', '!=', null], ['VehicleOwnershipStatus', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_vehicle_owners = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();

        $vehicle_owner = array_count_values(array_column($records->toArray(), 'VehicleOwnershipStatus'));
        $all_vehicle_owners = array_count_values(array_column($all_vehicle_owners->toArray(), 'VehicleOwnershipStatus'));
        arsort($vehicle_owner);
        arsort($all_vehicle_owners);

        return response()->json(["selected_vehicle_owners" => $vehicle_owner, "all_vehicle_owners" => $all_vehicle_owners]);
    }

    public function get_household_income(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('IncomeBucket')->where([['IncomeBucket', '!=', null], ['IncomeBucket', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_household_incomes = $records->get();
        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_marital_status);
        }  
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();
        $household_income = array_count_values(array_column($records->toArray(), 'IncomeBucket'));
        $all_household_incomes = array_count_values(array_column($all_household_incomes->toArray(), 'IncomeBucket'));;
        arsort($household_income);
        arsort($all_household_incomes);

        return response()->json(["all_household_incomes" => $all_household_incomes, "selected_household_incomes" => $household_income]);
    }

    public function get_employer(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('Employer')->where([['Employer', '!=', null], ['Employer', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_employers = $records->get();
        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_marital_status);
        }  
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        // Filter By Household Income
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();
        $employer = array_count_values(array_column($records->toArray(), 'Employer'));
        $all_employers = array_count_values(array_column($all_employers->toArray(), 'Employer'));;
        arsort($employer);
        arsort($all_employers);

        return response()->json(["all_employers" => $all_employers, "selected_employers" => $employer]);
    }

    public function get_risk_category(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('CreditRiskCategory')->where([['CreditRiskCategory', '!=', null], ['CreditRiskCategory', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_risk_categories = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }        

        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();

        $risk_category = array_count_values(array_column($records->toArray(), 'CreditRiskCategory'));
        $all_risk_categories = array_count_values(array_column($all_risk_categories->toArray(), 'CreditRiskCategory'));
        
        arsort($risk_category);
        arsort($all_risk_categories);

        return response()->json(["selected_risk_categories" => $risk_category, "all_risk_categories" => $all_risk_categories]);

    }

    public function get_lsm_group(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('LSMGroup')->where([['LSMGroup', '!=', null], ['LSMGroup', '!=', 'Unknown'], ['LSMGroup', '!=', 'LSM00']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_lsm_groups = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }        

        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();

        $lsm_group = array_count_values(array_column($records->toArray(), 'LSMGroup'));
        $all_lsm_groups = array_count_values(array_column($all_lsm_groups->toArray(), 'LSMGroup'));
        
        arsort($lsm_group);
        arsort($all_lsm_groups);

        return response()->json(["selected_lsm_groups" => $lsm_group, "all_lsm_groups" => $all_lsm_groups]);

    }

    public function get_property_valuation(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('PropertyValuationBucket')->where([['PropertyValuationBucket', '!=', null], ['PropertyValuationBucket', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_property_valuations = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();
        $property_valuation = array_count_values(array_column($records->toArray(), 'PropertyValuationBucket'));
        $all_property_valuations = array_count_values(array_column($all_property_valuations->toArray(), 'PropertyValuationBucket'));

        arsort($all_property_valuations);
        arsort($property_valuation);

        return response()->json(["all_property_valuations" => $all_property_valuations, "selected_property_valuations" => $property_valuation]);

    }

    public function get_property_count(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('PropertyCount')->where([['PropertyCount', '!=', null], ['PropertyCount', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_property_counts = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();
        $property_count = array_count_values(array_column($records->toArray(), 'PropertyCount'));
        $all_property_counts = array_count_values(array_column($all_property_counts->toArray(), 'PropertyCount'));

        arsort($all_property_counts);
        arsort($property_count);

        return response()->json(["all_property_counts" => $all_property_counts, "selected_property_counts" => $property_count]);

    }

    public function get_director_of_business(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('DirectorshipStatus')->where([['DirectorshipStatus', '!=', null], ['DirectorshipStatus', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_directors = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();
        $director_of_business = array_count_values(array_column($records->toArray(), 'DirectorshipStatus'));
        $all_directors = array_count_values(array_column($all_directors->toArray(), 'DirectorshipStatus'));

        arsort($all_directors);
        arsort($director_of_business);

        return response()->json(["all_directors" => $all_directors, "selected_directors" => $director_of_business]);

    }


    public function get_citizens_and_residents(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('id6', 'HasResidentialAddress')->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
    
        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();

        $citizen = 0;
        $resident = 0;

        foreach ($records as $row) {
    
            if($row->id6) {
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
        $records = \MeetPAT\EnrichedRecord::select('Generation')->where([['Generation', '!=', null], ['Generation', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_generations = $records->get();
        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }

        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();
        $generations = array_count_values(array_column($records->toArray(), 'Generation'));
        $all_generations = array_count_values(array_column($all_generations->toArray(), 'Generation'));

        arsort($generations);
        arsort($all_generations);

        return response()->json(["selected_generations" => $generations, "all_generations" => $all_generations]);
    }

    public function get_marital_statuses(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select('MaritalStatus')->where([['MaritalStatus', '!=', null], ['MaritalStatus', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_marital_status = $records->get();
        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }        

        $records = $records->get();
        $marital_statuses = array_count_values(array_column($records->toArray(), 'MaritalStatus'));
        $all_marital_status = array_count_values(array_column($all_marital_status->toArray(), 'MaritalStatus'));
        arsort($all_marital_status);
        arsort($marital_statuses);

        return response()->json(["all_marital_status" => $all_marital_status, "selected_marital_status" => $marital_statuses]);
    }

    public function get_area(Request $request) {

        // $records = \MeetPAT\BarkerStreetRecord::select('Area', 'GreaterArea')->where([['PropertyValuationBucket', '!=', null]])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $records = \MeetPAT\EnrichedRecord::select('Area')->where([['Area', '!=', null], ['Area', '!=', 'Unknown']])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_areas = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }
        
        $records = $records->get();
        $areas = array_count_values(array_column($records->toArray(), 'Area'));
        $all_areas = array_count_values(array_column($all_areas->toArray(), 'Area'));
        
        arsort($areas);
        arsort($all_areas);

        return response()->json(["all_areas" => $all_areas, "selected_areas" => $areas]);
    }

    // Part of Api for Progress Tracking
    public function get_job_que(Request $request) {

        $jobs = \MeetPAT\RecordsJobQue::where('user_id', $request->user_id)->with('audience_file')->orderBy('created_at', 'DESC')->whereDate('created_at', '=', Carbon::today()->toDateString());
        $running_jobs = \MeetPAT\RecordsJobQue::where('user_id', $request->user_id)->where(function($q) {
            $q->where('status', 'pending')->orWhere('status', 'running');
        })->count();

        return response()->json(["jobs" => $jobs->get()->toArray(), "jobs_running" => $running_jobs]);
    
    }

    // Store client filtered audience file
    public function save_filtered_audience(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::whereRaw("find_in_set('".$request->user_id."',affiliated_users)");
        $all_areas = $records->get();

        // Filter By Provinces
        if($request->selected_provinces) {
            $records = $records->whereIn('Province', $request->selected_provinces);
        } 
        // Filter By Municipalities
        if($request->selected_municipalities) {
            $records = $records->whereIn('Municipality', $request->selected_municipalities);
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
            $records = $records->whereIn('Generation', $request->selected_generations);
        }
        // Filter By Marital Status
        if($request->selected_marital_status) {
            $records = $records->whereIn('MaritalStatus', $request->selected_marital_status);
        }
        // Filter By Home Owners
        if($request->selected_home_owners) {
            $records = $records->whereIn('HomeOwnershipStatus', $request->selected_home_owners);
        }  
        // Filter By Property Valuation
        if($request->selected_property_valuations) {
            $records = $records->whereIn('PropertyValuationBucket', $request->selected_property_valuations);
        } 
        // Filter By Property Count
        if($request->selected_property_counts) {
            $records = $records->whereIn('PropertyCount', $request->selected_property_counts);
        }
        // Filter By Risk Categories
        if($request->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', $request->selected_risk_categories);
        }
        // Filter By Household Income
        if($request->selected_household_incomes) {
            $records = $records->whereIn('IncomeBucket', $request->selected_household_incomes);
        }
        if($request->selected_employers) {
            $records = $records->whereIn('Employer', $request->selected_employers);
        }
        // Filter By LSM Group
        if($request->selected_lsm_groups) {
            $records = $records->whereIn('LSMGroup', $request->selected_lsm_groups);
        }
        // Filter By Vehicle Ownership
        if($request->selected_vehicle_owners) {
            $records = $records->whereIn('VehicleOwnershipStatus', $request->selected_vehicle_owners);
        }
        // Filter By directors
        if($request->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', $request->selected_directors);
        }
        // Filter By areas
        if($request->selected_areas) {
            $records = $records->whereIn('Area', $request->selected_areas);
        }
        // Filter By Citizens and residents
        if($request->selected_citizen_vs_residents) {
            if(in_array("citizen", $request->selected_citizen_vs_residents)) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", $request->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        return response()->json();
    }

}