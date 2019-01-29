<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;

use Facebook\Facebook;
use FacebookAds\Api;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\CustomAudience;
use FacebookAds\Logger\CurlLogger;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MeetpatClientController extends Controller
{
    // Main Pages

    public function main()
    {
        return view('client.main');
    }

    public function sync_platform()
    {
        $user = \Auth::user();

        $has_facebook_ad_account = $user->facebook_ad_account;
        $has_google_ad_account = $user->google_ad_account;

        return view('client.dashboard.sync', ['has_facebook_ad_account' => $has_facebook_ad_account, 'has_google_ad_account' => $has_google_ad_account]);
    }

    public function upload_clients()
    {
        $user = \Auth::user();

        $has_google_adwords_acc = $user->google_ad_account;
        $has_facebook_ad_acc = $user->facebook_ad_account;

        return view('client.dashboard.upload_clients', ['has_google_adwords_acc' => $has_google_adwords_acc, 'has_facebook_ad_acc' => $has_facebook_ad_acc]);
    }

    // Update synced accounts

    public function update_facebook()
    {
        $user = \Auth::user();
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

            if($user->ad_account) {
                $user->ad_account->update(['access_token' => $_SESSION['facebook_access_token']]);
                $_SESSION['facebook_access_token'] = null;

                return redirect('/meetpat-client');
            } else {
                $new_ad_account = \MeetPAT\FacebookAdAccount::create(['user_id' => $user->id, 'access_token' => $_SESSION['facebook_access_token']]);
                
                if($new_ad_account) {
                    \Session::flash('success', 'Your facebook account has linked successfully.');
                    // Finally, destroy the session.
                    session_destroy();
                    return redirect('/meetpat-client');

                } else {
                    \Session::flash('error', 'There was a problem linking your account please contact MeetPAT for asssistance.');
                }
            }

          } else {

            $permissions = ['ads_management'];
            $loginUrl = $helper->getReAuthenticationUrl('https://infinite-coast-17182.herokuapp.com/register-facebook-ad-account', $permissions);
            // echo '<a href="' . $loginUrl . '">Log in with Facebook</a>';
          }

        return view('client.dashboard.update_facebook_acc', ['login_url' => $loginUrl]);
    }


    public function update_google()
    {
        $PRODUCTS = [
            ['AdWords API', 'https://www.googleapis.com/auth/adwords'],
            ['Ad Manager API', 'https://www.googleapis.com/auth/dfp'],
            ['AdWords API and Ad Manager API', 'https://www.googleapis.com/auth/adwords' . ' '
                . 'https://www.googleapis.com/auth/dfp']
        ];

        // $scopes = $PRODUCTS[2][1] . ' ' . trim(fgets($stdin));

        $oauth2 = new OAuth2(
            [
                'authorizationUri' => 'https://accounts.google.com/o/oauth2/v2/auth',
                'redirectUri' => 'urn:ietf:wg:oauth:2.0:oob',
                'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
                'clientId' => env('GOOGLE_CLIENT_ID'),
                'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
                'scope' => 'https://www.googleapis.com/auth/adwords' // $scope
            ]
        );

        $auth_uri = $oauth2->buildFullAuthorizationUri();

        return view('client.dashboard.update_google_acc', ['auth_uri' => $auth_uri]);
    }


    public function authenticate_authorization_code(Request $request)
    {
        $validatedData = $request->validate([
            'auth_code' => 'required',
            'user_id' => 'required',
        ]);

        $PRODUCTS = [
            ['AdWords API', 'https://www.googleapis.com/auth/adwords'],
            ['Ad Manager API', 'https://www.googleapis.com/auth/dfp'],
            ['AdWords API and Ad Manager API', 'https://www.googleapis.com/auth/adwords' . ' '
                . 'https://www.googleapis.com/auth/dfp']
        ];

        // $scopes = $PRODUCTS[2][1] . ' ' . trim(fgets($stdin));

        $oauth2 = new OAuth2(
            [
                'authorizationUri' => 'https://accounts.google.com/o/oauth2/v2/auth',
                'redirectUri' => 'urn:ietf:wg:oauth:2.0:oob',
                'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
                'clientId' => env('GOOGLE_CLIENT_ID'),
                'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
                'scope' => 'https://www.googleapis.com/auth/adwords' // $scope
            ]
        );

        $user = \MeetPAT\User::find($request->user_id);
        $client = $user->client();

        $code = $request->auth_code;

        $oauth2->setCode($code);
        $authToken = $oauth2->fetchAuthToken();

        if($authToken) {
            $has_ad_account = \MeetPAT\GoogleAdwordsAccount::where('user_id', $user->id)->first();
            if(!$has_ad_account) {
                \MeetPAT\GoogleAdwordsAccount::create(['user_id' => $user->id, 'access_token' => $authToken['refresh_token'] ]);
            } else {
                $has_ad_account->update(['access_token' => $authToken['refresh_token'] ]);
            }
            \Session::flash('success', 'Your account has been authorized successfully.');
        } else {
            \Session::flash('error', 'An error occured. Check authorization code or contact MeetPAT for assistance.');
        }

        return redirect("/meetpat-client");

    }

    public function handle_update_google_acc(Request $request)
    {

        return response(200);
    }
}
