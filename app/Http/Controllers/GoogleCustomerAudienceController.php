<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class GoogleCustomerAudienceController extends Controller
{
    //
    public function register_ad_account_id()
    {
        $user = \Auth::User();

        $user_google_account = Socialite::driver('google')->user();

        if($user_google_account) {
            \Session::flash('success', 'Your Access token: ' . $user_google_account->access_token);
        } else {
            \Session::flash('error', 'Your Google Access Token could not be container');

        }


        return view('auth.google_ad_account', []);

    }
}
