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
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v2.4',
          ]);
          
          $helper = $fb->getRedirectLoginHelper();
          
          if (!$request->session()->exists('facebook_access_token')) {
            $request->session()->get('facebook_access_token', 'default');
          }
          
          if (!$request->session()->exists('facebook_access_token')) {
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
            Api::init(
            env('FACEBOOK_APP_ID'),
            env('FACEBOOK_APP_SECRET'),
            $request->session()->get('facebook_access_token') // Your user access token
              );
          } else {
            $permissions = ['ads_management'];
            $loginUrl = $helper->getLoginUrl('https://infinite-coast-17182.herokuapp.com/register-facebook-add-account', $permissions);
          }

            return view('auth.facebook_ad_account', ['login_url' => $loginUrl, 'login_fb' => $request->session()->get('facebook_access_token')]);
    }
}
