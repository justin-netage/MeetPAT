<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use FacebookAds\Api;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class FacebookCustomerAudienceController extends Controller
{
    //

    public function register_ad_account_id(Request $request) 
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

                return redirect('/meetpat-client/upload-clients');
            } else {
                $new_ad_account = \MeetPAT\FacebookAdAccount::create(['user_id' => $user->id, 'access_token' => $_SESSION['facebook_access_token']]);
                
                if($new_ad_account) {
                    \Session::flash('success', 'Your facebook account has linked successfully.');
                    // Finally, destroy the session.
                    session_destroy();
                    return redirect('/meetpat-client/upload-clients');

                } else {
                    \Session::flash('error', 'There was a problem linking your account please contact MeetPAT for asssistance.');

                }
            }

          } else {

            $permissions = ['ads_management'];
            $loginUrl = $helper->getReAuthenticationUrl('https://infinite-coast-17182.herokuapp.com/register-facebook-ad-account', $permissions);
            // echo '<a href="' . $loginUrl . '">Log in with Facebook</a>';
          }
            return view('auth.facebook_ad_account', ['login_url' => $loginUrl]);
    }

    public function upload_facebook_customers(Request $request) 
    {

      return view('client.dashboard.upload_facebook_clients');
    }

    public function upload_facebook_customers_handle(Request $request)
    {
      $file_extension = null;
      $reader = null;
      $spreadsheet = null;
      $sheetData = null;

    if($request->file('custom_audience')->isValid()) {
      $response_text = 'valid file';

      $csv_file = $request->file('custom_audience');
      $imageName = time().'.'.$request->image->getClientOriginalExtension();
      $sent = \Storage::disk('s3')->put($imageName, file_get_contents('C:\inetpub\wwwroot\Netage\MeetPAT\meetpat\text.txt'), 'public');
      $fileName = Storage::disk('s3')->put($fileName);
      
      } else {
        $response_text = 'in valid file';
      }

      $file = $request->file('custom_audience');

      return response($sent, 200)
                  ->header('Content-Type', 'text/plain');
    }

    public function download_sample_file()
    {
      return \Storage::disk('s3')->url('meetpat/public/sample/example_audience_file.csv');
    }
    
}
