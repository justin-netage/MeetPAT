<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use MeetPAT\Mail\ContactSend;

class ContactController extends Controller
{
    //

    public function contact()
    {
        return view('clients.contact');
    }

    public function apply()
    {

        return view('clients.apply');
    }

    public function send_message(Request $request)
    {
        $client_email = $request->email;

        $data = [ 'name' => $request->name, 'email' => $request->email, 'message' => $request->message];

        \Mail::to('justin@netage.co.za')->send(new ContactSend($data));

        return response(200);
    }
}
