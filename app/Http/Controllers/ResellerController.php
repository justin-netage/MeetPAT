<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MeetPAT\Mail\NewUser;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;

class ResellerController extends Controller
{
    //

    public function main() 
    {
        return view('reseller.main');
    }

    public function create_client_view()
    {
        return view('admin.clients.create_client', array('route' => 'reseller-save-client'));

    }

    public function save_client(Request $request)
    {
        $success_message = 'A new user has been added successfully.';

        $validatedData = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'business_name' => 'required|max:255',
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

            $new_client = \MeetPAT\MeetpatClient::create(['user_id' => $new_user->id, 'active' => 1, 'reseller_id' => $request->reseller_id]);
            $uploads = \MeetPAT\ClientUploads::create(["user_id" => $new_user->id, "upload_limit" => 10000, "uploads" => 0]);

            $client_details = \MeetPAT\MeetpatClientDetail::create(['user_id' => $new_user->id,
                                                    'business_registered_name' => $request->business_name,
                                                    'contact_first_name' => '',
                                                    'contact_last_name' => '',
                                                    'contact_email_address' => '',
                                                    'business_contact_number' => '',
                                                    'business_registration_number' => '',
                                                    'business_vat_number' => '',
                                                    'business_postal_address' => '',
                                                    'business_physical_address' => '',
                                                    'client_first_name' => '',
                                                    'client_last_name' => '',
                                                    'client_contact_number' => '',
                                                    'client_email_address' => '',
                                                    'client_postal_address' => '']);
        } else {
            $new_user = \MeetPAT\User::create(['name' => $request->firstname . ' ' . $request->lastname,
                                               'email' => $request->email,
                                               'password' => \Hash::make($request->password) ]);
                                                           
            $new_client = \MeetPAT\MeetpatClient::create(['user_id' => $new_user->id, 'active' => 1]);
            $uploads = \MeetPAT\ClientUploads::create(["user_id" => $new_user->id, "upload_limit" => 10000, "uploads" => 0]);

            $client_details = \MeetPAT\MeetpatClientDetail::create(['user_id' => $new_user->id,
                                                    'business_registered_name' => $request->business_name,
                                                    'contact_first_name' => '',
                                                    'contact_last_name' => '',
                                                    'contact_email_address' => '',
                                                    'business_contact_number' => '',
                                                    'business_registration_number' => '',
                                                    'business_vat_number' => '',
                                                    'business_postal_address' => '',
                                                    'business_physical_address' => '',
                                                    'client_first_name' => '',
                                                    'client_last_name' => '',
                                                    'client_contact_number' => '',
                                                    'client_email_address' => '',
                                                    'client_postal_address' => '']);
        }
        
        if($request->send_email)
        {
            $data = [ 'name' => $request->name, 'email' => $request->email, 'password' => $request->password, 'message' => ''];

            \Mail::to($request->email)->send(new NewUser($data));

            $success_message = 'A new user has been added successfully and an email has been sent to the new users email address (' . $request->email. ').';
        }

        return back()->with('success', $success_message);
    }

    public function clients_view()
    {

        $user_api_token = \Auth::user()->api_token;

        return view('reseller.clients.clients', ['user_api_token' => $user_api_token]);
    }

    public function get_users(Request $request)
    {
        $currentPage = $request->current;

        if($request->search_term)
        {
            $users_array = \MeetPAT\User::select(["id", "name", "email", "created_at"])->with(array('client', 'client_details'))
            ->whereHas('client_details', function($query) use ($request) { 
                $query->where('business_registered_name', 'ilike', '%' . $request->search_term . '%');})->has('client')->doesnthave('client_removal')
            ->orWhere('email', 'ilike', '%' . $request->search_term . '%')->has('client')->doesnthave('client_removal')
            ->orWhere('name', 'ilike', '%' . $request->search_term . '%')->has('client')->doesnthave('client_removal')
            ->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $users_array = \MeetPAT\User::select(["id", "name", "email", "created_at"])->has('client')->doesnthave('client_removal')->with(array('client', 'client_details'))->orderBy('created_at', 'desc')->paginate(10);
        }

        return response()->json($users_array);

    }


}
