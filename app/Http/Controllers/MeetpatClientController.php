<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

class MeetpatClientController extends Controller
{
    // Main Pages

    public function main()
    {
        $user_google_account = Socialite::driver('google')->user();

        if($user_google_account) {
            \Session::flash('success', 'Your Access token: ' . $user_google_account->access_token);
        } else {
            \Session::flash('error', 'Your Google Access Token could not be container');

        }

        return view('client.main');
    }

    public function sync_platform()
    {

        return view('client.dashboard.sync');
    }

    public function upload_clients()
    {
        $user = \Auth::user();

        $has_facebook_ad_account = null;
        $has_google_ad_account = null;

        if($user->ad_account) {
            $has_facebook_ad_account = true;
        }

        // if($user->ad_word_account) {
        //     $has_google_ad_account = true;
        // }

        return view('client.dashboard.upload_clients', ['has_facebook_ad_account' => $has_facebook_ad_account, 'has_google_ad_account' => $has_google_ad_account]);
    }
}
