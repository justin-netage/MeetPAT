<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class GoogleCustomerAudienceController extends Controller
{
    //
    public function register_ad_account_id()
    {
        $user = \Auth::User();

 


        return view('auth.google_ad_account', []);

    }
}
