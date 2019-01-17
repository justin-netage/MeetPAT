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
use Illuminate\Validation\Rule;

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

      $validator = \Validator::make($request->all(), [
        'audience_name' => 'required|unique:facebook_audience_files,audience_name,' . $request->user_id,
        'user_id' => 'required',
        'audience_file' => 'required|mimes:csv,txt',
    ]);
    
    if ($validator->fails())
    {
        return response()->json(['errors'=>$validator->errors()]);
    } else {

      $directory_used = null;
      $file_uploaded = null;
      $csv = null;
      
      if($request->file('audience_file')->isValid()) {
        
        $response_text = 'valid file';

        $csv_file = $request->file('audience_file');
        $fileName = uniqid() . '_' . str_replace(" ", "_", $request->audience_name);

        if(env('APP_ENV') == 'production') {
          $directory_used = \Storage::disk('s3')->makeDirectory('client/custom-audience/user_id_' . $request->user_id);

          if($directory_used) {
            $file_uploaded = \Storage::disk('s3')->put('client/custom-audience/user_id_' . $request->user_id . '/' . $fileName, file_get_contents($csv_file));

          }
        } else {
          $directory_used = \Storage::disk('local')->makeDirectory('client/custom-audience/user_id_' . $request->user_id);

          if($directory_used) {
            $file_uploaded = \Storage::disk('local')->put('client/custom-audience/user_id_' . $request->user_id . '/' . $fileName, file_get_contents($csv_file));

          }
        }

        if($directory_used and $file_uploaded) {
          $file_exists = \MeetPAT\FacebookAudienceFile::where([['file_unique_name', '==', $fileName], ['user_id', '==', $request->user_id]])->first();
          if($file_exists) {
            $file_exists->update(['file_unique_name' => $fileName]);
  
          } else {
            \MeetPAT\FacebookAudienceFile::create(['user_id' => $request->user_id, 'audience_name' => $request->audience_name, 'file_unique_name' => $fileName]);
  
          }

          function readCSV($csvFile){
            $file_handle = fopen($csvFile, 'r');
            while (!feof($file_handle) ) {
              $line_of_text[] = fgetcsv($file_handle, 0);
            }
            fclose($file_handle);
            return $line_of_text;
          }
           
          $csv = readCSV($request->file('audience_file')); 
          foreach ( $csv as $c ) {
              $firstColumn = $c[0];
              $secondColumn = $c[1];
              $thirdColumn = $c[2];  
              $fourthColumn = $c[3];
          }

          if($csv) {
            $new_job = \MeetPAT\FacebookJobQue::create(['user_id' => $request->user_id, 'total_audience' => sizeof($csv) - 1, 'audience_captured' => 0, 'percentage_complete' => 0, 'job_status' => 'ready']);
          }
  
        }

      } else {
        $response_text = 'in valid file';
      }

      return response()->json($new_job);
    
    }

  }

    // The request handler

    public function facebook_upload_handler(Request $request)
    {
      $job = \MeetPAT\FacebookJobQue::find($request->job_id);

      sleep(2);

      $job->increment('audience_captured');

      return response()->json($new_job);
 
    }

    public function download_sample_file()
    {
      return \Storage::disk('s3')->url('meetpat/public/sample/example_audience_file.csv');
    }

    public function start_job_que(Request $request)
    {

      
    }
    
}
