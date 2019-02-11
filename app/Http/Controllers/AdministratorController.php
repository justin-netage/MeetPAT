<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MeetPAT\Mail\NewUser;


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
        $users = \DB::table("users")->select("*")->whereNOTIn('id', function($query) {
            $query->select('user_id')->from('administrators');
        })->get();

        return $users;
    }
    // Get User Count
    public function user_count()
    {
        $user_count = \MeetPAT\User::count();

        return $user_count;
    }
    // create new client
    public function create_user(Request $request)
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

        $new_user = \MeetPAT\User::create(['name' => $request->firstname . ' ' . $request->lastname,
                                             'email' => $request->email,
                                             'password' => \Hash::make($request->password) ]);

        $new_client = \MeetPAT\MeetpatClient::create(['user_id' => $new_user->id, 'active' => 1]);

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

        if (!filter_var($request->user_email, FILTER_VALIDATE_EMAIL) and !\App\User::where('email', $request->email)->first()) {
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
            $symbol    = preg_match("@[-!$%^&*()_+|~=`{}\[\]:\";'<>?,.\/]@", $request->new_password);

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

    public function users_view()
    {
        $users = \MeetPAT\User::all();

        return view('admin.clients.users', ['users' => $users]);
    }

    public function create_user_view()
    {
        return view('admin.clients.create_user');
    }

    // route functions for user files to download

    public function display_users()
    {

        return view('admin.clients.user_files');
    }

}
