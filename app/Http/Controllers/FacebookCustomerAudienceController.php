<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use FacebookAds\Api;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class FacebookCustomerAudienceController extends Controller
{
    //

    public function register_ad_account_id(Request $request) 
    {
        $loginUrl = null;
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
          ]);
          
          $helper = $fb->getRedirectLoginHelper();
          
          if (!isset($_SESSION['facebook_access_token'])) {
            $_SESSION['facebook_access_token'] = null;
          }
          
          if (!$_SESSION['facebook_access_token']) {
            $helper = $fb->getRedirectLoginHelper();
            try {
              $_SESSION['facebook_access_token'] = (string) $helper->getAccessToken();
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
          
          if ($_SESSION['facebook_access_token']) {
            echo "You are logged in!";
          } else {
            $permissions = ['ads_management'];
            $loginUrl = $helper->getLoginUrl('https://infinite-coast-17182.herokuapp.com/register-facebook-add-account', $permissions);
            // echo '<a href="' . $loginUrl . '">Log in with Facebook</a>';
          }
            return view('auth.facebook_ad_account', ['login_url' => $loginUrl]);
    }
}
