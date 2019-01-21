<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
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
      // Main variables

      $user = \MeetPAT\User::find($request->user_id);
      $client_facebook = $user->ad_account();

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
          $audience_file = \MeetPAT\FacebookAudienceFile::where([['file_unique_name', '==', $fileName], ['user_id', '==', $request->user_id]])->first();
          if($audience_file) {
            $audience_file->update(['file_unique_name' => $fileName]);
  
          } else {
            $audience_file = \MeetPAT\FacebookAudienceFile::create(['user_id' => $request->user_id, 'audience_name' => $request->audience_name, 'file_unique_name' => $fileName]);
  
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
            $new_job = \MeetPAT\FacebookJobQue::create(['user_id' => $request->user_id, 'facebook_audience_file_id' => $audience_file->id, 'total_audience' => sizeof($csv) - 1, 'audience_captured' => 0, 'percentage_complete' => 0, 'job_status' => 'ready']);
            if($new_job and $client_facebook) {
              
              // Test upload to custom audience

              $access_token = 'EAAKaRXpnm10BAMztV56n9LEbE4tMZAdfHsNkOSTtLBFM5LKaEON9RqjZBD6tvBYBo0V4Ovu5dF3DfLEVyRVQzETZBM8g6HX8Fd0GojDmA8fotYuPZAoZAOgpN1FFSvPee7MFU1sB2VulZBsrnwf5AUEGNXe1jflgfRAZCeAFoYCCarxXKUf4PLVUIEEj58Tcgrh8Qkv4IuyuYsV9q54HsLOwMJYOspXXdMZD';
              $app_secret = env('FACEBOOK_APP_SECRET');
              $app_id = env('FACEBOOK_APP_ID');
              $id = '2182368842043371';

              $api = Api::init($app_id, $app_secret, $access_token);
              $api->setLogger(new CurlLogger());

              $fields = array(
              );
              $params = array(
                'name' => $audience_file->audience_name,
                'subtype' => 'CUSTOM',
                'description' => 'People who purchased on my website',
                'customer_file_source' => 'USER_PROVIDED_ONLY',
              );

              echo json_encode((new AdAccount($id))->createCustomAudience(
                $fields,
                $params
              )->exportAllData(), JSON_PRETTY_PRINT);
            }
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
      // Main variables
      $job = \MeetPAT\FacebookJobQue::find($request->job_id);
      $user = \MeetPAT\User::find($job->user_id);
      $client_facebook = $user->ad_account();
      // methods

      function get_percentage($total, $number)
      {
        if ( $total > 0 ) {
         return (int)round($number / ($total / 100),2);
        } else {
          return 0;
        }
      }

      if($job) {
        
          $job->increment('audience_captured');
          $job->update(['job_status' => 'busy', 'percentage_complete' => get_percentage($job->total_audience, $job->audience_captured)]);
        
      } else {

        return response()->json($request);
      }

      return response()->json($job);
 
    }

    public function download_sample_file()
    {
      return \Storage::disk('s3')->url('meetpat/public/sample/example_audience_file.csv');
    }

    public function start_job_que(Request $request)
    {

      
    }
    
}
