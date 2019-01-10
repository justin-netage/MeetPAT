<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class FacebookCustomerAudienceController extends Controller
{
    //

    public function register_ad_account_id(Request $request) 
    {
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
          ]);
          
          $helper = $fb->getRedirectLoginHelper();
          
          if ($request->session()->exists('facebook_access_token')) {
            $_SESSION['facebook_access_token'] = null;
          }
          
          if ($request->session()->exists('facebook_access_token')) {
            $helper = $fb->getRedirectLoginHelper();
            try {
                $request->session()->put('key', (string) $helper->getAccessToken());
            } catch(FacebookResponseException $e) {
              // When Graph returns an error
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(FacebookSDKException $e) {
              // When validation fails or other local issues
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }
          }
          
          if ($request->session()->exists('facebook_access_token')) {
            echo "You are logged in!";
          } else {
            $permissions = ['ads_management'];
            $loginUrl = $helper->getLoginUrl('http://localhost:8888/marketing-api/', $permissions);
          }

            return view('auth.facebook_ad_account', ['login_url' => $loginUrl, 'access_token_stored' => $access_token_stored]);
    }
}
