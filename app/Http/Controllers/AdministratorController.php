<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MeetPAT\Mail\NewUser;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;


class AdministratorController extends Controller
{
    // Main Administrator Page
    public function main()
    {

        return view('admin.main');
    }
    // Get all users
    public function users()
    {
        $users = \MeetPAT\User::has('client')->with(['client', 'client_uploads', 'similar_audience_credits', 'client_details'])->get();

        return $users;
    }
    // Get User Count
    public function user_count()
    {
        $user_count = \MeetPAT\User::count();

        return $user_count;
    }
    // create new client
    public function create_client(Request $request)
    {
        $success_message = 'A new user has been added successfully.';

        $validatedData = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => array(
                                'required',
                                'string',
                                'min:8',
                                'max: 20',
                                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/',
                                'confirmed'
            )
        ]);

        if($request->give_api_key) {
            $new_user = \MeetPAT\User::create(['name' => $request->firstname . ' ' . $request->lastname,
                                               'email' => $request->email,
                                               'password' => \Hash::make($request->password),
                                               'api_token' => Str::random(60) ]);

            $new_client = \MeetPAT\MeetpatClient::create(['user_id' => $new_user->id, 'active' => 1]);
            $uploads = \MeetPAT\ClientUploads::create(["user_id" => $new_user->id, "upload_limit" => 10000, "uploads" => 0]);
        } else {
            $new_user = \MeetPAT\User::create(['name' => $request->firstname . ' ' . $request->lastname,
                                               'email' => $request->email,
                                               'password' => \Hash::make($request->password) ]);
                                                           
            $new_client = \MeetPAT\MeetpatClient::create(['user_id' => $new_user->id, 'active' => 1]);
            $uploads = \MeetPAT\ClientUploads::create(["user_id" => $new_user->id, "upload_limit" => 10000, "uploads" => 0]);
        }
        
        if($request->send_email)
        {
            $data = [ 'name' => $request->name, 'email' => $request->email, 'password' => $request->password, 'message' => ''];

            \Mail::to($request->email)->send(new NewUser($data));

            $success_message = 'A new user has been added successfully and an email has been sent to the new users email address (' . $request->email. ').';
        }

        return back()->with('success', $success_message);
    }
    // edit client details username, email etc...
    public function edit_user(Request $request)
    {

        $user = \MeetPAT\User::find($request->user_id);
        $response = ["users_id" => $request->user_id, "sent_mail" => "false", "email_valid" => "false", "user_name_valid" => "false", "password_valid" => "false", "password_change" => "false"];

        if (!filter_var($request->user_email, FILTER_VALIDATE_EMAIL) and !\MeetPAT\User::where('email', $user->email)->first()) {
            $response["email_valid"] = "false";

        } else {
            $response["email_valid"] = "true";
        }

        if($request->user_name) {
            $response["user_name_valid"] = "true";
        } else {
            $response["user_name_valid"] = "false";
        }

        if($request->new_password)
        {
            $response["password_change"] = "true";

            $uppercase = preg_match('@[A-Z]@', $request->new_password);
            $lowercase = preg_match('@[a-z]@', $request->new_password);
            $number    = preg_match('@[0-9]@', $request->new_password);
            $symbol    = preg_match("@[-!$%^&*()\@_+|~=`{}\[\]:\";'<>?,.\/]@", $request->new_password);

            if(!$uppercase || !$lowercase || !$number || !$symbol || strlen($request->new_password) < 8) {

                $response["password_valid"] = "false";

            } else {
                $response["password_change"] = "true";
                $response["password_valid"] = "true";
            }

        } else {
            $response["password_change"] = "false";
        }

        if($user and filter_var($request->user_email, FILTER_VALIDATE_EMAIL) and $request->user_name)
        {
            $user->name = $request->user_name;
            $user->email = $request->user_email;

            if($request->new_password)
            {
                $user->password = \Hash::make($request->new_password);

                if(filter_var($request->send_mail, FILTER_VALIDATE_BOOLEAN))
                {
                    $data = [ 'name' => $request->user_name, 'email' => $request->user_email, 'password' => $request->new_password, 'message' => '' ];

                    \Mail::to($request->user_email)->send(new NewUser($data));  

                    $response["sent_mail"] = "true";
                } else {
                    $response["sent_mail"] = "false";
                }

            } 

            $user->save();
        }

        return $response;

    }

    // delete a client 
    public function delete(Request $request)
    {
        $deleted = false;
        $user = \MeetPAT\User::find($request->user_id);
        $client = $user->client()->first();

        if($user and $client)
        {
            $delete_client = $client->delete();
            $deleted = $user->delete();   
        }

        return response()->json(['email' => $user->email, 'id' => $user->id, 'deleted' => $deleted]);
    }

    public function unique_email(Request $request)
    {
        $user_email = \MeetPAT\User::where('email', $request->email)->first();
        $user = \MeetPAT\User::find($request->user_id);

        if($user_email and $request->email != $user->email)
        {
            $email_used = "true"; 
        } else {
            $email_used = "false"; 
        }

        return response()->json(['email_used' => $email_used]);
    }

    // Set inactive status of a client 

    public function active_change(Request $request)
    {
        $user = \MeetPAT\User::find($request->user_id);
        $status_message = 'An Error has ocured. Please contact us for support.';
        $user_type = 'none';
        $user_was_active = 0;

        if($user and $user->client) {
            $status_message = 'User is a client.';
            $user_type = 'client';

            if($user->client->active)
            {
                $user_was_active = 1;
                $user->client->update(['active' => 0 ]);
            } else {
                $user->client->update(['active' => 1 ]);
            }
            
        } else {
            $status_message = 'User not found';
        }

        return response()->json(['message' => $status_message, 'user_type' => $user_type, 'user_was_active' => $user_was_active]);
    }

    // Views

    // public function users_view()
    // {
    //     $users = \MeetPAT\User::all();

    //     return view('admin.clients.users', ['users' => $users]);
    // }

    public function users_view()
    {
        $users = \MeetPAT\User::with(['client', 'client_uploads', 'similar_audience_credits'])->get();

        return view('admin.clients.users', ['users' => $users]);
    }

    public function create_client_view()
    {
        return view('admin.clients.create_client');
    }

    public function get_users(Request $request)
    {
        $currentPage = $request->current;

        if($request->search_term)
        {
            $users_array = \MeetPAT\User::select(["id", "name", "email", "created_at"])->has('client')->doesnthave('client_removal')
                            ->with('client')->where([['name', 'ilike', '%'.$request->search_term.'%']])->has('client')->doesnthave('client_removal')
                            ->orWhere([['email', 'ilike', '%'.$request->search_term.'%']])->has('client')->doesnthave('client_removal')
                            ->orderBy('created_at', 'desc')->paginate(10);

        } else {
            $users_array = \MeetPAT\User::select(["id", "name", "email", "created_at"])->has('client')->doesnthave('client_removal')->with('client')->orderBy('created_at', 'desc')->paginate(10);
        }

        return response()->json($users_array);

    }

    // route functions for user files to download
    public function get_user_files(Request $request)
    {
        $currentPage = $request->current;

        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        
        });

        if($request->searchPhrase)
        {
            $files_array = \MeetPAT\AudienceFile::select(["id","audience_name","file_source_origin", "file_unique_name", "created_at"])->where([['user_id', '=', $request->user_id], ['audience_name', 'ilike', '%'.$request->searchPhrase.'%']])->orderBy('created_at', 'desc')->paginate($request->rowCount);

            foreach($files_array as $key=>$file)
            {
                $files_array->items()[$key]["audience_name"] = explode(" - ", $files_array->items()[$key]["audience_name"])[0];
                $files_array->items()[$key]["file_source_origin"] = ucwords(str_replace("_", " ", $files_array->items()[$key]["file_source_origin"]));
                $files_array->items()[$key]["created_at"] = Carbon::parse($files_array->items()[$key]["created_at"])->addHour(2);

                if(env('APP_ENV') == 'production') {
                    if(\Storage::disk('s3')->exists('client/client-records/' . 'user_id_' . $request->user_id . '/' . $files_array[$key]["file_unique_name"] . '.csv'))
                    {
                        $files_array->items()[$key]["download"] = \Storage::disk('s3')->temporaryUrl('client/client-records/' . 'user_id_' . $request->user_id . '/' . $files_array[$key]["file_unique_name"] . '.csv', now()->addMinutes(5), ['Content-Type' => 'text/csv', 'ResponseContentType' => 'text/csv', 'ResponseContentDisposition' => 'attachment; filename=' . explode(" - ", $files_array->items()[$key]["audience_name"])[0] . ".csv"]);
                        $files_array->items()[$key]["size"] = round(\Storage::disk('s3')->size('client/client-records/user_id_' . $request->user_id . '/' . $files_array[$key]["file_unique_name"] . '.csv') / 1024 / 1024, 2) . "MB";
                    } else {
                        $files_array->items()[$key]["download"] = "/404";
                        $files_array->items()[$key]["size"] = "N\A";
                    }

                } else {
                    if(\Storage::disk('local')->exists('client/client-records/' . 'user_id_' . $request->user_id . '/' . $files_array[$key]["file_unique_name"] . '.csv'))
                    {
                        $files_array->items()[$key]["download"] = "/404";
                        $files_array->items()[$key]["size"] = round(\Storage::disk('local')->size('client/client-records/user_id_' . $request->user_id . '/' . $files_array[$key]["file_unique_name"] . '.csv') / 1024 / 1024, 2) . "MB";
                    } else {
                        $files_array->items()[$key]["download"] = "/404";
                        $files_array->items()[$key]["size"] = "N\A";
                    }
                }

                
            }

        } else {
            $files_array = \MeetPAT\AudienceFile::select(["id","audience_name","file_source_origin", "file_unique_name", "created_at"])->where('user_id', $request->user_id)->orderBy('created_at', 'desc')->paginate($request->rowCount);
            
            foreach($files_array as $key=>$file)
            {
                $files_array->items()[$key]["audience_name"] = explode(" - ", $files_array->items()[$key]["audience_name"])[0];
                $files_array->items()[$key]["file_source_origin"] = ucwords(str_replace("_", " ", $files_array->items()[$key]["file_source_origin"]));
                $files_array->items()[$key]["created_at"] = Carbon::parse($files_array->items()[$key]["created_at"])->addHour(2);

                if(env('APP_ENV') == 'production') {
                    if(\Storage::disk('s3')->exists('client/client-records/' . 'user_id_' . $request->user_id . '/' . $files_array[$key]["file_unique_name"] . '.csv'))
                    {
                        $files_array->items()[$key]["download"] = \Storage::disk('s3')->temporaryUrl('client/client-records/' . 'user_id_' . $request->user_id . '/' . $files_array[$key]["file_unique_name"] . '.csv', now()->addMinutes(5), ['Content-Type' => 'text/csv', 'ResponseContentType' => 'text/csv', 'ResponseContentDisposition' => 'attachment; filename=' . explode(" - ", $files_array->items()[$key]["audience_name"])[0] . ".csv"]);
                        $files_array->items()[$key]["size"] = round(\Storage::disk('s3')->size('client/client-records/user_id_' . $request->user_id . '/' . $files_array[$key]["file_unique_name"] . '.csv') / 1024 / 1024, 2) . "MB";
                    } else {
                        $files_array->items()[$key]["download"] = "/404";
                        $files_array->items()[$key]["size"] = "N\A";
                    }
                } else {
                    if(\Storage::disk('local')->exists('client/client-records/' . 'user_id_' . $request->user_id . '/' . $files_array[$key]["file_unique_name"] . '.csv'))
                    {
                        $files_array->items()[$key]["download"] = "/404";
                        $files_array->items()[$key]["size"] = round(\Storage::disk('local')->size('client/client-records/user_id_' . $request->user_id . '/' . $files_array[$key]["file_unique_name"] . '.csv') / 1024 / 1024, 2) . "MB";
                    } else {
                        $files_array->items()[$key]["download"] = "/404";
                        $files_array->items()[$key]["size"] = "N\A";
                    }
                }

            }
        }


        

        return response()->json(array("current" => $files_array->currentPage(), "rowCount" => $files_array->count(), "rows" => $files_array->items(), "total" => $files_array->total()));
        
    }

    public function display_user_files($user_id)
    {   
        $user = \MeetPAT\User::find($user_id);
        $user_api_token = \Auth::user()->api_token;
        $client_audience_files = \MeetPAT\AudienceFile::where('user_id', $user_id)->orderBy('created_at', 'DESC')->get();

        return view('admin.clients.user_files', ['user_api_token' => $user_api_token, 'audience_files' => $client_audience_files, 'user' => $user]);
    }

    public function clear_user_uploads(Request $request) {
        $user = \MeetPAT\User::find($request->user_id);

        if($user->client_uploads)
        {
            $user->client_uploads->update(['uploads' => 0]);

        } else {
            return response()->json(["message" => "failed", "user" => $user]);
        }
        
        return response()->json(["message" => "cleared", "user" => $user]);
    }

    public function remove_affiliate(Request $request)
    {
        $user = \MeetPAT\User::find($request->user_id);
        $records_count = 0;

        if($user->client)
        {
            $records = \MeetPAT\BarkerStreetRecord::whereRaw("CAST(".$user->id." as text) = ANY(string_to_array(affiliated_users, ','))");
            if($records->count())
            {
                $records_array = $records->get();
                $first_record = $records->first();
                
                $affiliate_array = explode(",", $first_record->affiliated_users);
                $affiliate_updated = implode(",", array_diff($affiliate_array, array($user->id)));
                
                \MeetPAT\BarkerStreetRecord::whereRaw("CAST(".$user->id." as text) = ANY(string_to_array(affiliated_users, ','))")->update(["affiliated_users" => $affiliate_updated]);

                $records = \MeetPAT\BarkerStreetRecord::whereRaw("CAST(".$user->id." as text) = ANY(string_to_array(affiliated_users, ','))");
                $records_count = $records->count();
            }

        } else {
            return response()->json(["message" => "error"], 500);
        }

        return response()->json(["message" => "success", "records" => $records_count], 200);
    }

    public function delete_file(Request $request)
    {
        $audience_file = \MeetPAT\AudienceFile::find($request->file_id);
        
        if ($audience_file)
        {
            $file_exists = false;
            if(env('APP_ENV') == 'production')
            {
                $file_exists = \Storage::disk('s3')->exists('client/client-records/user_id_' . $audience_file->user_id . '/' . $audience_file->file_unique_name . '.csv');

            } else {
                $file_exists = \Storage::disk('local')->exists('client/client-records/user_id_' . $audience_file->user_id . '/' . $audience_file->file_unique_name . '.csv');

            }

            if($file_exists)
            {
                if(env('APP_ENV') == 'production')
                {
                    $file_deleted = \Storage::disk('s3')->delete('client/client-records/user_id_' . $audience_file->user_id . '/' . $audience_file->file_unique_name . '.csv');
                    $audience_file->delete();
                } else {
                    $file_deleted = \Storage::disk('local')->delete('client/client-records/user_id_' . $audience_file->user_id . '/' . $audience_file->file_unique_name . '.csv');
                    $audience_file->delete();
                }

            } else {
                $audience_file->delete();
            }
    
        } else {
            return response()->json(['message' => 'error', 'text' => 'record not found'], 500);
        }

        return response()->json(['message' => 'success', 'text' => 'record and file has been removed'], 200);
    }

    public function set_upload_limit(Request $request) 
    {

        $user = \MeetPAT\User::find($request->user_id);
        $user_updated = false;

        if($user->client_uploads)
        {
            $user_updated = $user->client_uploads->update(['upload_limit' => $request->new_upload_limit]);
        } else {
            $user_updated = \MeetPAT\ClientUploads::create(['user_id' => $user->id, 'upload_limit' => $request->new_upload_limit]);
        }

        if($user_updated == false)
        {
            return response()->json(["status" => "error", "message" => "An error has occured please contact support."], 500);
        } 

        $client_uploads = \MeetPAT\ClientUploads::where('user_id', $user->id)->first();

        return response()->json(["status" => "success", "message" => "User credit limit has been updated.", "client_uploads" => $client_uploads], 200);
    }

    public function set_similar_audience_limit(Request $request) 
    {
        $user = \MeetPAT\User::find($request->user_id);
        $user_updated = false;

        if($user->similar_audience_credits)
        {
            $user_updated = $user->similar_audience_credits->update(['credit_limit' => $request->new_credit_limit]);
            
        } else {
            $user_updated = \MeetPAT\SimilarAudienceCredit::create(['user_id' => $user->id, 'credit_limit' => $request->new_credit_limit]);

        }

        if($user_updated == false)
        {
            return response()->json(["status" => "error", "message" => "An error has occured please contact support."], 500);
        } 

        $client_credits = \MeetPAT\SimilarAudienceCredit::where('user_id', $user->id)->first();

        return response()->json(["status" => "success", "message" => "User upload limit has been updated.", "client_uploads" => $client_credits], 200);
    }

    public function enriched_data_tracking() {

        $years = DB::table('enriched_data_trackings')->select(DB::raw("DISTINCT EXTRACT(YEAR FROM created_at) as year"))->orderBy('year')->get();
        $months = DB::table('enriched_data_trackings')->select(DB::raw("DISTINCT EXTRACT(MONTH FROM created_at) as month, to_char(to_timestamp (EXTRACT(Month FROM created_at)::text, 'MM'), 'TMmon') as name"))->orderBy('month')->get();
        
        return view('admin.enriched_data_tracking', ['years' => $years, 'months' => $months]);
    }

    public function get_enriched_data_tracking_day(Request $request) {

        $enriched_data_tracking = DB::table('enriched_data_trackings')->select(DB::raw('EXTRACT(DAY FROM created_at) as day, SUM(received) as received, SUM(sent) as sent, EXTRACT(YEAR FROM created_at) as year'))->whereRaw('EXTRACT(Month FROM created_at) = ' . $request->month . ' and EXTRACT(YEAR FROM created_at) = ' . $request->year)->groupBy('day', 'year')->get();

        return response()->json(array("data" => $enriched_data_tracking, "request" => $request->toArray()));

    }

    public function get_enriched_data_tracking_monthly(Request $request) {

        $enriched_data_tracking = DB::table('enriched_data_trackings')->select(DB::raw('EXTRACT(MONTH FROM created_at) as month, SUM(received) as received, SUM(sent) as sent, EXTRACT(YEAR FROM created_at) as year'))->whereRaw('EXTRACT(YEAR FROM created_at) = ' . $request->year)->groupBy('month', 'year')->get();

        return response()->json(array("data" => $enriched_data_tracking, "request" => $request->toArray()));
    }

    /** BEGIN Client Controllers */

    public function all_clients(Request $request)
    {
        $user = \MeetPAT\User::where('api_token', $request->api_token)->first()->admin;

        if($user) {
            return array('data' => \MeetPAT\User::join('meetpat_clients', 'users.id', '=', 'meetpat_clients.user_id')->get());
        } else {
            return abort(401);
        }
    }

    public function clients_view()
    {
        $user_api_token = \Auth::user()->api_token;
        $clients = \MeetPAT\User::join('meetpat_clients', 'users.id', '=', 'meetpat_clients.user_id')->where('user_id', '!=', 72)->get();

        return view('admin.clients.clients', ['user_api_token' => $user_api_token, 'clients' => $clients]);
    }

    public function get_client(Request $request)
    {
        $client = \MeetPAT\User::with(array('client', 'client_uploads'))->where('id', $request->user_id)->first();

        return response()->json(array("client" => $client));
    }

    /** END Client Controllers */

    /** BEGIN Reseller Controllers */

    public function resellers_view()
    {
        return view('admin.resellers.resellers');
    }

    public function create_reseller_view()
    {
        return view('admin.resellers.create_reseller');
    }

    public function create_reseller(Request $request)
    {
        $success_message = 'A new reseller has been added successfully.';

        $validatedData = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => array(
                                'required',
                                'string',
                                'min:8',
                                'max: 20',
                                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/',
                                'confirmed'
            )
        ]);

        if($request->give_api_key) {
            $new_user = \MeetPAT\User::create(['name' => $request->firstname . ' ' . $request->lastname,
                                               'email' => $request->email,
                                               'password' => \Hash::make($request->password),
                                               'api_token' => Str::random(60) ]);
        } else {
            $new_user = \MeetPAT\User::create(['name' => $request->firstname . ' ' . $request->lastname,
                                               'email' => $request->email,
                                               'password' => \Hash::make($request->password) ]);            
                                            }

        $new_client = \MeetPAT\Reseller::create(['user_id' => $new_user->id, 'active' => 1]);
        
        if($request->send_email)
        {
            $data = [ 'name' => $request->name, 'email' => $request->email, 'password' => $request->password, 'message' => ''];

            \Mail::to($request->email)->send(new NewReseller($data));

            $success_message = 'A new user has been added successfully and an email has been sent to the new users email address (' . $request->email. ').';
        }

        return back()->with('success', $success_message);
    }

    /** END Reseller Controllers */

    public function running_jobs() 
    {

        return view('admin.running_jobs');

    }

    public function get_running_jobs(Request $request)
    {
        $is_admin = \MeetPAT\User::find(\MeetPAT\User::where("api_token", $request["api_token"])->get()[0]->id)->admin()->get();
        
        if($is_admin) {
            $user = \MeetPAT\User::where("api_token", $request->api_token)->get();
            $jobs = \MeetPAT\RecordsJobQue::with(array('process_tracking', 'user'))->whereDate('created_at', '>' , Carbon::now()->subMonth())->orderBy('created_at', 'desc')->get();
            
            return response()->json($jobs);
        } else {

            return abort(401);
        }
        
    }

    public function delete_user(Request $request)
    {
        $user = \MeetPAT\User::where(["name" => $request->user_name, "id" => $request->user_id])->get();
                
        if($user) {
            $user_update = \MeetPAT\User::find($user[0]->id);
            $user_update->email = "removed_" . Carbon::now()->timestamp . "_" . $user_update->email;
            $user_update->save();
            $user_update->client->update(['active' => 0 ]);
            
            $create_job = \MeetPAT\DeleteUserJobQueue::create(["user_id" => $user[0]->id, "status" => "pending"]);
                        
        } else {
            return response()->json(["status" => "error", "message" => "The selected user has no records in the database."]);
        }

        
        return response()->json(["status" => "success", "message" => "Success. Client Queued for Deletion"]);
    }

}
