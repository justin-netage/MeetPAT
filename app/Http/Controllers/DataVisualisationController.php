<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

ini_set('memory_limit', '-1');


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
        $audience_names = [];
        $audience_files = \MeetPAT\AudienceFile::where("user_id", $request->user_id)->get();

        foreach($audience_files as $audience_file) 
        {
            array_push($audience_names, explode(" - ", $audience_file->audience_name)[0]);
        }

        if(!in_array($request->audience_name, $audience_names)) {
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
                return response(array("status" => "error", "message" => "Server could not get file."));
            }
            //\MeetPAT\Jobs\EnrichRecords::dispatch();
            return response()->json(array("status" => "success", "message" => "File uploaded successfully and queued for processing."));
        } else {
            return response()->json(array("status" => "error", "message" => "Audience File name has already been used."));
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

    // Part of Api for Progress Tracking
    public function get_job_que(Request $request) {

        $jobs = \MeetPAT\RecordsJobQue::where('user_id', $request->user_id)->with('audience_file')->orderBy('created_at', 'DESC');
        $running_jobs = \MeetPAT\RecordsJobQue::where('user_id', $request->user_id)->where(function($q) {
            $q->where('status', 'pending')->orWhere('status', 'running');
        })->count();

        return response()->json(["jobs" => $jobs->get(), "jobs_running" => $running_jobs]);
    
    }

    // Store client filtered audience file
    public function save_filtered_audience(Request $request)
    {
        $records = \MeetPAT\EnrichedRecord::select(['FirstName',
        'Middlename','Surname','CleanPhone','Email1'])->whereRaw("find_in_set('".$request->user_id."',affiliated_users)");


        // Filter By Provinces
        if($request->provinceContacts[0]) {
            $records = $records->whereIn('Province', explode(",", $request->provinceContacts[0]));
        } 
        // Filter By Municipalities
        if($request->municipalityContacts[0]) {
            $records = $records->whereIn('Municipality', explode(",", $request->municipalityContacts[0]));
        }
        // Filter By Age Groups
        if($request->AgeContacts[0]) {
            $records = $records->whereIn('AgeGroup', explode(",", $request->AgeContacts[0]));
        }
        // Filter By Gender
        if($request->GenderContacts[0]) {
            $records = $records->whereIn('Gender', explode(",", $request->GenderContacts[0]));
        }
        // Filter By Population Group
        if($request->populationContacts[0]) {
            $records = $records->whereIn('PopulationGroup', explode(",", $request->populationContacts[0]));
        }
        // Filter By Generation Group
        if($request->generationContacts[0]) {
            $records = $records->whereIn('Generation', explode(",", $request->generationContacts[0]));
        }
        // Filter By Marital Status
        if($request->maritalStatusContacts[0]) {
            $records = $records->whereIn('MaritalStatus', explode(",", $request->maritalStatusContacts[0]));
        }
        // Filter By Home Owners
        if($request->homeOwnerContacts[0]) {
            $records = $records->whereIn('HomeOwnershipStatus', explode(",", $request->homeOwnerContacts[0]));
        }  
        // Filter By Property Valuation
        if($request->propertyValuationContacts[0]) {
            $records = $records->whereIn('PropertyValuationBucket', explode(",", $request->propertyValuationContacts[0]));
        } 
        // Filter By Property Count
        if($request->propertyCountBucketContacts[0]) {
            $records = $records->whereIn('PropertyCountBucket', explode(",", $request->propertyCountBucketContacts[0]));
        }
        // Filter By Risk Categories
        if($request->riskCategoryContacts[0]) {
            $records = $records->whereIn('CreditRiskCategory', explode(",", $request->riskCategoryContacts[0]));
        }
        // Filter By Household Income
        if($request->houseHoldIncomeContacts[0]) {
            $records = $records->whereIn('IncomeBucket', explode(",", $request->houseHoldIncomeContacts[0]));
        }
        if($request->employerContacts[0]) {
            $records = $records->whereIn('Employer', explode(",", $request->employerContacts[0]));
        }
        // Filter By LSM Group
        if($request->lsmGroupContacts[0]) {
            $records = $records->whereIn('LSMGroup', explode(",", $request->lsmGroupContacts[0]));
        }
        // Filter By Vehicle Ownership
        if($request->vehicleOwnerContacts[0]) {
            $records = $records->whereIn('VehicleOwnershipStatus', explode(",", $request->vehicleOwnerContacts[0]));
        }
        // Filter By directors
        if($request->directorsContacts[0]) {
            $records = $records->whereIn('DirectorshipStatus', explode(",", $request->directorsContacts[0]));
        }
        // Filter By areas
        if($request->areaContacts[0]) {
            $records = $records->whereIn('Area', explode(",", $request->areaContacts[0]));
        }
        // Filter By Citizens and residents
        if($request->citizenVsResidentsContacts[0]) {
            if(in_array("citizen", explode(",", $request->citizenVsResidentsContacts[0]))) {
                $records = $records->where('id6', '!=', '');
                

            } else if(in_array("resident", explode(",", $request->citizenVsResidentsContacts[0]))) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get()->toArray();
        $decryptded_array = [];
        foreach($records as $record)
        {
            if($record["FirstName"] == "NULL") {
                $record["FirstName"] = "";
            } 

            if($record["Middlename"]== "NULL") {
                $record["Middlename"] = "";
            }

            if($record["Surname"]== "NULL") {
                $record["Surname"] = "";
            }
            
            if($record["CleanPhone"]== "NULL") {
                $record["CleanPhone"] = "";
            }

            if($record["Email1"]== "NULL") {
                $record["Email1"] = "";
            }

            // if($record["Email2"]) {
            //     $record["Email2"] = $record["Email2"];
            // }

            // if($record["Email3"]) {
            //     $record["Email3"] = $record["Email3"];
            // }

            // if($record["MobilePhone1"]) {
            //     $record["MobilePhone1"] = $record["MobilePhone1"];
            // }

            // if($record["MobilePhone2"]) {
            //     $record["MobilePhone2"] = $record["MobilePhone2"];
            // }

            // if($record["MobilePhone3"]) {
            //     $record["MobilePhone3"] = $record["MobilePhone3"];
            // }

            // if($record["WorkPhone1"]) {
            //     $record["WorkPhone1"] = $record["WorkPhone1"];
            // }

            // if($record["WorkPhone2"]) {
            //     $record["WorkPhone2"] = $record["WorkPhone2"];
            // }

            // if($record["WorkPhone3"]) {
            //     $record["WorkPhone3"] = $record["WorkPhone3"];
            // }

            // if($record["HomePhone1"]) {
            //     $record["HomePhone1"] = $record["HomePhone1"];
            // }

            // if($record["HomePhone2"]) {
            //     $record["HomePhone2"] = $record["HomePhone2"];
            // }

            // if($record["HomePhone3"]) {
            //     $record["HomePhone3"] = $record["HomePhone3"];
            // }

            // if($record["MaritalStatus"])
            // {
            //     if($record["MaritalStatus"] == "True")
            //     {
            //         $record["MaritalStatus"] = "Yes";
            //     } else {
            //         $record["MaritalStatus"] = "No";
            //     }
            // }

            // if($record["HomeOwnershipStatus"])
            // {
            //     if($record["HomeOwnershipStatus"] == "True")
            //     {
            //         $record["HomeOwnershipStatus"] = "Yes";
            //     } else {
            //         $record["HomeOwnershipStatus"] = "No";
            //     }
            // }

            // if($record["VehicleOwnershipStatus"])
            // {
            //     if($record["VehicleOwnershipStatus"] == "True")
            //     {
            //         $record["VehicleOwnershipStatus"] = "Yes";
            //     } else {
            //         $record["VehicleOwnershipStatus"] = "No";
            //     }
            // }

            // if($record["DirectorshipStatus"])
            // {
            //     if($record["DirectorshipStatus"] == "True")
            //     {
            //         $record["DirectorshipStatus"] = "Yes";
            //     } else {
            //         $record["DirectorshipStatus"] = "No";
            //     }
            // }

            // if($record["DeceasedStatus"])
            // {
            //     if($record["DeceasedStatus"] == "True")
            //     {
            //         $record["DeceasedStatus"] = "Yes";
            //     } else {
            //         $record["DeceasedStatus"] = "No";
            //     }
            // }

            // if($record["HasResidentialAddress"])
            // {
            //     if($record["HasResidentialAddress"] == "True")
            //     {
            //         $record["HasResidentialAddress"] = "Yes";
            //     } else {
            //         $record["HasResidentialAddress"] = "No";
            //     }
            // }

            array_push($decryptded_array, $record);
        }

        $parser = new \CsvParser\Parser(',', '', "\n");
        $csv = $parser->fromArray($decryptded_array); 
        $csv_str = $parser->toString($csv);
        $fileName = uniqid() . "_" . time();

        if(env('APP_ENV') == 'production')
        {
            $directory_used = \Storage::disk('s3')->makeDirectory('client/saved-audiences/');
            $file_uploaded = \Storage::disk('s3')->put('client/saved-audiences/user_id_' . $request->user_id . '/' . $fileName  . ".xls", $csv_str);

        } else {
            $directory_used = \Storage::disk('local')->makeDirectory('client/saved-audiences/');
            $file_uploaded = \Storage::disk('local')->put('client/saved-audiences/user_id_' . $request->user_id . '/' . $fileName  . ".xls", $csv_str);
        }

        $saved_audience = \MeetPAT\SavedFilteredAudienceFile::create(["user_id" => $request->user_id, "file_unique_name" => $fileName, "file_name" => $request->file_name]);

        return response()->json(["saved_file" => $saved_audience, "request" => $request->provinceContacts]);
    }

    public function get_saved_audiences(Request $request)
    {
        $files = \MeetPAT\SavedFilteredAudienceFile::where('user_id', $request->user_id)->get()->toArray();
        $new_array = [];

        foreach($files as $file)
        {
            
            if(env('APP_ENV') == 'production')
            {
                $file_exists = \Storage::disk('s3')->exists('client/saved-audiences/user_id_' . $file["user_id"] . '/' . $file["file_unique_name"] . '.xls');
                
                if($file_exists)
                {
                    $file["link"] = \Storage::disk('s3')->temporaryUrl('client/saved-audiences/' . 'user_id_' . $file["user_id"] . '/' . $file["file_unique_name"] . '.xls', now()->addMinutes(1440));
    
                } else {
                    $file["link"] = "404";
                }

            } else {
                $file_exists = \Storage::disk('local')->exists('client/saved-audiences/user_id_' . $file["user_id"] . '/' . $file["file_unique_name"] . '.xls');
                
                if($file_exists)
                {
                    $file["link"] = \Storage::disk('local')->url('client/saved-audiences/' . 'user_id_' . $file["user_id"] . '/' . $file["file_unique_name"] . '.xls');
    
                } else {
                    $file["link"] = "404";
                }
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
            $actual_file = \Storage::disk('s3')->delete('client/client-records/user_id_' . $request->user_id . '/' . $request->file_unique_name  . ".xls");
        } else {
            $actual_file = \Storage::disk('local')->delete('client/saved-audiences/user_id_' . $request->user_id . '/' . $request->file_unique_name  . ".xls");
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