<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class FacebookCustomerAudienceController extends Controller
{
    //

    public function register_ad_account_id(Request $request) 
    {
        $access_token_stored = false;

        if($request->session()->get('facebook_accessToken')) {
            $access_token_stored = $request->session()->get('facebook_accessToken');
        }

        $fb = new \Facebook\Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v2.10',
            ]);
          
            $helper = $fb->getRedirectLoginHelper();
            // $accessToken = $fb->getAccessToken();
            
            $permissions = ['email']; // Optional permissions
            $loginUrl = $helper->getLoginUrl('https://infinite-coast-17182.herokuapp.com/register-facebook-add-account', $permissions);

            return view('auth.facebook_ad_account', ['login_url' => $loginUrl, 'access_token_stored' => $access_token_stored]);
    }
}
