<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

ini_set('memory_limit', '-1');
set_time_limit(0);

class DataVisualisationController extends Controller
{
    //

    public function index()
    {
        $user = \Auth::user();
        $records = \MeetPAT\EnrichedRecord::whereRaw("CAST(".$user->id." as text) = ANY(string_to_array(affiliated_users, ','))")->count();
        $user_jobs = \MeetPAT\RecordsJobQue::where('user_id', $user->id);
        $user_update_jobs_pending = \MeetPAT\UpdateRecordsJobQueue::where([['user_id', '=', $user->id], ['status', '=', 'pending']])->count();
        $processing_all_records = \MeetPAT\FilterJobQueue::where([['user_id', '=', $user->id], ['status', '=', 'processing']])->count();
        $user_jobs_running = $user_jobs->where(function($q) {
            $q->where('status', 'pending')->orWhere('status', 'running');
        })->count();
        //$user_jobs_complete = \MeetPAT\RecordsJobQue::where(['user_id' => $user->id, 'status' => 'done'])->first();

        if($user_jobs_running or $user_update_jobs_pending or $processing_all_records) {
            if($user_jobs_running  or $processing_all_records) {
                return view('client.data_visualisation.records_updating');
            } else if($user_update_jobs_pending) {
                return view('client.dashboard.upload.updating');
            } else {
                return abort(500);
            }
            
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
        $user_update_jobs_pending = \MeetPAT\UpdateRecordsJobQueue::where([['user_id', '=', $user->id], ['status', '=', 'pending']])->count();
        $user_jobs_running = $user_jobs->where(function($q) {
            $q->where('status', 'pending')->orWhere('status', 'running');
        })->count();
        if($user_jobs_running or $user_update_jobs_pending)
        {
            if($user_jobs_running) {
                return view('client.data_visualisation.records_updating');
            } else if($user_update_jobs_pending) {
                return view('client.dashboard.upload.updating');
            } else {
                return abort(500);
            }
            
        } else {
            return view('enrich_data_upload');
        }
        
    }

    public function large_data_upload(Request $request)
    {
        
        $actual_file = null;
        $audience_names = [];
        $audience_files = \MeetPAT\AudienceFile::where("user_id", $request->user_id)->get();
        $has_job_running = \MeetPAT\RecordsJobQue::where("status", "pending")->orWhere("status", "running")->where("user_id", $request->user_id)->get();

        foreach($audience_files as $audience_file) 
        {
            array_push($audience_names, explode(" - ", $audience_file->audience_name)[0]);
        }

        if(!$has_job_running->count()) {
            if(!in_array($request->audience_name, $audience_names)) {
                if(env('APP_ENV') == 'production') {
                    $actual_file = \Storage::disk('upload_s3')->get('fixed_files/' . $request->file_id . ".csv");
                    $queue_file = \Storage::disk('s3')->put('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id  . ".csv", $actual_file);
                    $actual_file = \Storage::disk('s3')->copy('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id  . ".csv", 'Temp/Enrichment/' . $request->file_id . '.csv');
                } else {
                    $actual_file = \Storage::disk('local')->get('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id  . ".csv");
                }
        
                // $array = array_map("str_getcsv", explode("\n", $actual_file));
                // unset($array[0]);
                // unset($array[sizeof($array)]);
        
                if($actual_file) {
               
                    $audience_file = \MeetPAT\AudienceFile::create(['user_id' => $request->user_id, 'audience_name' => $request->audience_name . " - " . time(), 'file_unique_name' => $request->file_id, 'file_source_origin' => $request->file_source_origin]);
                    $created_job_que = \MeetPAT\RecordsJobQue::create(
                        ['user_id' => $request->user_id, 'audience_file_id' => $audience_file->id, 'status' => 'pending', 'records' => 0, 'records_completed' => 0]
                    );
        
                } else {
                    return response(array("status" => "error", "message" => "Server could not get file."));
                }
                //\MeetPAT\Jobs\EnrichRecords::dispatch();
                return response()->json(array("status" => "success", "message" => "File uploaded successfully and queued for processing."));
            } else {
                return response()->json(array("status" => "error", "message" => "Audience File name has already been used."));
            }
        } else {
            return response()->json(array("status" => "error", "message" => "A file is already being processed at the moment. Refresh this window."));
        }

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

    
    public function queue_filter_job(Request $request) {
        
        function check_empty($value) {
            if(!$value) {
                return "";
            } else {
                return $value;
            }
        }
        // Clear Prevoius jobs.
        $previous_filter_jobs = \MeetPAT\FilterJobQueue::where([["user_id", "=", $request->user_id]]);
        $previous_filter_jobs->delete();

        $new_job = \MeetPAT\FilterJobQueue::create([
            "user_id" => $request->user_id, "filter_type" => "filter", "status" => "pending",
            "provinces" => check_empty($request->provinces), "municipalities" => check_empty($request->municipalities), "areas" => check_empty($request->areas),
            "genders" => check_empty($request->genders), "population_groups" => check_empty($request->population_groups), "age_groups" => check_empty($request->age_groups),
            "generations" => check_empty($request->generations), "citizens_vs_residents" => check_empty($request->citizens_vs_residents), "marital_statuses" => check_empty($request->marital_statuses),
            "marital_statuses" => check_empty($request->marital_statuses), "home_ownership_statuses" => check_empty($request->home_ownership_statuses),
            "property_count_buckets" => check_empty($request->property_count_buckets), "vehicle_ownership_statuses" => check_empty($request->vehicle_ownership_statuses),
            "primary_property_types" => check_empty($request->primary_property_types), "risk_categories" => check_empty($request->risk_categories), "lsm_groups" =>check_empty($request->lsm_groups),
            "income_buckets" => check_empty($request->income_buckets), "company_directorship_status" => check_empty($request->company_directorship_status), "custom_variable_1" => check_empty($request->custom_variable_1)
        ]);

        return response()->json($new_job);
    }

    public function check_job_status(Request $request) {
        $job = \MeetPAT\FilterJobQueue::find($request->job_id);

        return $job->status;
    }

    // Get Location data with count.
    public function get_location_data(Request $request) {

        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/get-location-data?" . $query_params));
        
        return response()->json($records);

    }
    // Get Demographic data
    public function get_demographic_data(Request $request) {

        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/get-demographic-data?" . $query_params));
        
        return response()->json($records);

    }
    // Get Assets Data 
    public function get_assets_data(Request $request) {

        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/get-assets-data?" . $query_params));
        
        return response()->json($records);

    }
    // Get Financial Data
    public function get_financial_data(Request $request) {

        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/get-financial-data?" . $query_params));
        
        return response()->json($records);

    }

    public function get_custom_variable_data(Request $request) {

        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/get-custom-variables?" . $query_params));

        return response()->json($records);
    }
    

    public function get_records_count(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records_count = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/count?" . $query_params));

        return response($records_count[0]->count);
    }

    public function get_municipalities(Request $request) {

        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/municipality?" . $query_params));
        
        return response()->json($records);

    }

    public function get_provinces(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/province?" . $query_params));
        
        return response()->json($records);
    }

    public function get_ages(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/age-group?" . $query_params));

        return response()->json($records);
    }

    public function get_genders(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/gender?" . $query_params));

        return response()->json($records);

    }

    public function get_population_groups(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/population-group?" . $query_params));

        return response()->json($records);
    }

    public function get_home_owner(Request $request) 
    {
        
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/home-ownership-status?" . $query_params));

        return response()->json($records);
    }

    public function get_vechicle_owner(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/vehicle-ownership-status?" . $query_params));

        return response()->json($records);
    }

    public function get_household_income(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/income-bucket?" . $query_params));

        return response()->json($records);
    }

    public function get_employer(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/employer?" . $query_params));

        return response()->json($records);
    }

    public function get_risk_category(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/risk-category?" . $query_params));

        return response()->json($records);

    }

    public function get_lsm_group(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/lsm-group?" . $query_params));

        return response()->json($records);

    }

    public function get_property_valuation(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/property-valuation-bucket?" . $query_params));

        return response()->json($records);

    }

    public function get_property_count_bucket(Request $request)
    {
        
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/property-count-bucket?" . $query_params));

        return response()->json($records);
    }

    public function get_director_of_business(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/directorship-status?" . $query_params));

        return response()->json($records);

    }


    public function get_citizens_and_residents(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/citizen-vs-resident?" . $query_params));

        return response()->json($records[0]);
    }

    public function get_generations(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/generation?" . $query_params));

        return response()->json($records);
    }

    public function get_marital_statuses(Request $request)
    {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/marital-status?" . $query_params));

        return response()->json($records);
    }

    public function get_area(Request $request) {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/area?" . $query_params));

        return response()->json($records);
        
    }

    public function get_primary_property_type(Request $request) {
        $query_params = http_build_query($request->all());
        $records = json_decode(file_get_contents("https://ancient-depths-59870.herokuapp.com/records/primary-property-type?" . $query_params));

        return response()->json($records);
        
    }

    // Part of Api for Progress Tracking
    public function get_job_que(Request $request) {

        $jobs = \MeetPAT\RecordsJobQue::where('user_id', $request->user_id)->with('audience_file')->orderBy('created_at', 'DESC')->take(2)->get();
        $running_jobs = \MeetPAT\RecordsJobQue::where('user_id', $request->user_id)->where(function($q) {
            $q->where('status', 'pending')->orWhere('status', 'running');
        })->count();

        $running_processes = \MeetPAT\FilterJobQueue::where('user_id', $request->user_id)->where(function($q) {
            $q->where('status', 'processing');
        })->count();

        return response()->json(["jobs" => $jobs, "jobs_running" => $running_jobs, "jobs_processing" => $running_processes]);
    
    }

    // Store client filtered audience file
    public function save_filtered_audience(Request $request)
    {
        function check_empty($value) {
            if(!$value) {
                return "";
            } else {
                return $value;
            }
        }

        $fileName = uniqid() . "_" . time();

        $saved_audience = \MeetPAT\SavedFilteredAudienceFile::create(["user_id" => $request->user_id, "file_unique_name" => $fileName, "file_name" => $request->file_name]);
        $query_params = $request->toArray();
        unset($query_params["file_name"]);
        $query_params["file_unique_name"] = $fileName;$query_params["file_id"] = $saved_audience->id;
        $query_params["province"] = check_empty($request["provinceContacts"][0]); $query_params["area"] = check_empty($request["areaContacts"][0]); $query_params["municipality"] = check_empty($request["municipalityContacts"][0]);
        $query_params["age_group"] = check_empty($request["AgeContacts"][0]); $query_params["gender"] = check_empty($request["GenderContacts"][0]); $query_params["population_group"] = check_empty($request["populationContacts"][0]);
        $query_params["generation"] = check_empty($request["generationContacts"][0]); $query_params["citizenship_indicator"] = check_empty($request["citizenshipIndicatorContacts"][0]); $query_params["marital_status"] = check_empty($request["maritalStatusContacts"][0]);
        $query_params["home_ownership_status"] = check_empty($request["homeOwnerContacts"][0]); $query_params["risk_category"] = check_empty($request["riskCategoryContacts"][0]); $query_params["income_bucket"] = check_empty($request["houseHoldIncomeContacts"][0]);
        $query_params["directorship_status"] = check_empty($request["directorsContacts"][0]); $query_params["vehicle_ownership_status"] = check_empty($request["vehicleOwnerContacts"][0]); $query_params["property_count_bucket"] = check_empty($request["propertyCountBucketContacts"][0]);
        $query_params["property_valuation_bucket"] = check_empty($request["propertyValuationContacts"][0]); $query_params["lsm_group"] = check_empty($request["lsmGroupContacts"][0]);$query_params["primary_property_type"] = check_empty($request["primaryPropertyTypeContacts"][0]);
        $query_params["custom_variable_1"] = check_empty($request["branchContacts"][0]);
        
        $filtered_audience = \MeetPAT\FilteredAudienceFile::create($query_params);
        // Queue file to be saved.
        $create_job = \MeetPAT\SaveFilesJobQueue::create(array("user_id" => $request->user_id, "status" => "pending", "saved_file_id" => $saved_audience->id, "saved_filters_id" => $filtered_audience->id, "number_of_records" => $request->number_of_contacts));
        
        return response()->json(["job" => $create_job, "request" => $request->toArray()]);
    }

    public function check_job_complete(Request $request)
    {
        $file_save_job = \MeetPAT\SaveFilesJobQueue::find($request->id);

        return response()->json(["job" => $file_save_job]);
    }

    public function get_saved_audiences(Request $request)
    {
        $files = \MeetPAT\SavedFilteredAudienceFile::where('user_id', $request->user_id)->orderBy('created_at', 'desc')->get()->toArray();
        $new_array = [];

        foreach($files as $file)
        {
            
            $file_exists = \Storage::disk('s3')->exists('client/saved-audiences/user_id_' . $file["user_id"] . '/' . $file["file_unique_name"] . '.csv');
            
            if($file_exists)
            {
                $file["link"] = \Storage::disk('s3')->temporaryUrl(
                    'client/saved-audiences/' . 'user_id_' . $file["user_id"] . '/' . $file["file_unique_name"] . '.csv', now()->addMinutes(1440),
                    ['Content-Type' => 'text/csv',
                        'ResponseContentType' => 'text/csv',
                        'ResponseContentDisposition' => 'attachment; filename=' . $file["file_name"] . ".csv"]);

            } else {
                $file["link"] = "404";
            }

            array_push($new_array, $file);

        }

        return response()->json($new_array);
    }

    public function delete_filtered_audience_file(Request $request)
    {
        
        $file = \MeetPAT\SavedFilteredAudienceFile::where([["file_unique_name", '=', $request->file_unique_name], ["user_id", "=", $request->user_id ]])->first();
        $file_deleted = $file->delete();

        if(env('APP_ENV') == 'production') {
            $actual_file = \Storage::disk('s3')->delete('client/saved-audiences/user_id_' . $request->user_id . '/' . $request->file_unique_name  . ".csv");
        } else {
            $actual_file = \Storage::disk('local')->delete('client/saved-audiences/user_id_' . $request->user_id . '/' . $request->file_unique_name  . ".csv");
        }

        return response()->json(["message" => "successfully deleted file."]);
    }

    public function save_file_names(Request $request)
    {

        $changed_files = [];
        
        foreach(array_keys($request->all()) as $value)
        {
            $saved_file = \MeetPAT\SavedFilteredAudienceFile::where([["file_unique_name", "=", $value], ["user_id", "=", $request->user_id]])->first();

            if($saved_file)
            {
                $saved_file->update([ "file_name" => $request[$value] ]);
                array_push($changed_files, $saved_file);
            } 
            
        }
        return response()->json(array("changed" => $changed_files));
        //return response()->json(["message" => "success", "data" => $request->user_id]);
    }

}