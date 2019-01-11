<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class MeetpatClientController extends Controller
{
    // Main Pages

    public function main()
    {

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
