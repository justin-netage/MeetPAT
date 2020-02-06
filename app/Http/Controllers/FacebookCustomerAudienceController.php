<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use FacebookAds\Api;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use FacebookAds\Object\User;
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

            if($user->facebook_ad_account) {
                $user->facebook_ad_account->update(['access_token' => $_SESSION['facebook_access_token']]);
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
                    \Session::flash('error', 'There was a problem linking your account please contact MeetPAT for assistance.');
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
        'file_source_origin' => 'required'
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

        // Create Modal and table for new jobs that check for facebook and google syncing custom audiences 
        // $request->facebook_custom_audience $request->google_custom_audience
        // Use api routes to handle the jobs if both are used or just one keep status when job is done (They will be handled globally not separately, change all facebook instances that handle separatley and change it to a global handler)

        $unique_id = uniqid();
        $facebook_job = null;
        $google_job = null;

        if($facebook_custom_audience) {
          $facebook_job = \MeetPAT\UploadJobQue::create(['user_id' => $request->user_id, 'unique_id' => $unique_id, 'platform' => $facebook_custom_audience, 'status' => 'pending']);
        }

        if($google_custom_audience) {
          $google_job = \MeetPAT\UploadJobQue::create(['user_id' => $request->user_id, 'unique_id' => $unique_id, 'platform' => $google_custom_audience, 'status' => 'pending']);

        }

        $new_job = \MeetPAT\UploadJobQue::where('unique_id', $unique_id);

        // if($directory_used and $file_uploaded) {
        //   $audience_file = \MeetPAT\FacebookAudienceFile::where([['file_unique_name', '==', $fileName], ['user_id', '==', $request->user_id]])->first();
        //   if($audience_file) {
        //     $audience_file->update(['file_unique_name' => $fileName]);
  
        //   } else {
        //     $audience_file = \MeetPAT\FacebookAudienceFile::create(['user_id' => $request->user_id, 'audience_name' => $request->audience_name, 'file_unique_name' => $fileName, 'file_source_origin' => $request->file_source_origin]);
  
        //   }

        //   function readCSV($csvFile){
        //     $file_handle = fopen($csvFile, 'r');
        //     while (!feof($file_handle) ) {
        //       $line_of_text[] = fgetcsv($file_handle, 0);
        //     }
        //     fclose($file_handle);
        //     return $line_of_text;
        //   }
           
        //   $csv = readCSV($request->file('audience_file')); 
        //   foreach ( $csv as $c ) {
        //       $firstColumn = $c[0];
        //       $secondColumn = $c[1];
        //       $thirdColumn = $c[2];  
        //       $fourthColumn = $c[3];
        //   }

        //   if($csv) {
        //     $new_job = \MeetPAT\FacebookJobQue::create(['user_id' => $request->user_id, 'facebook_audience_file_id' => $audience_file->id, 'total_audience' => sizeof($csv) - 1, 'audience_captured' => 0, 'percentage_complete' => 0, 'job_status' => 'ready']);
          
        //   }
  
        // }

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
      $client_facebook = $user->facebook_ad_account();
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

    public function deauthorize(Request $request)
    {
      $user = \MeetPAT\User::find((\MeetPAT\User::where('api_token', $request->api_token)->get()[0]->id))->facebook_ad_account->delete();

      return "success";
    }

    public function create_custom_audience(Request $request)
    {

      // hash function
      function normalizeAndHash($value)
      {
          return hash('sha256', strtolower(trim($value)));
      }

      $user = \MeetPAT\User::find($request->user_id);
      $saved_filtered_audience_file = \MeetPAT\SavedFilteredAudienceFile::find($request->filtered_audience_id);
      $add_acc = $user->facebook_ad_account;
      $access_token = $add_acc->access_token;
      $app_secret = env('FACEBOOK_APP_SECRET');
      $app_id = env('FACEBOOK_APP_ID');
      $id = "act_" . $add_acc->ad_account_id;

      if(env('APP_ENV') == 'production')
      {
        $file_exists = \Storage::disk('s3')->exists('client/saved-audiences/user_id_' . $user->id . '/' . $saved_filtered_audience_file->file_unique_name  . ".csv");
      } else {
        $file_exists = \Storage::disk('local')->exists('client/saved-audiences/user_id_' . $user->id . '/' . $saved_filtered_audience_file->file_unique_name  . ".csv");
      }

      if($file_exists)
      {
        if(env('APP_ENV') == 'production')
        {
          $saved_audience_file = \Storage::disk('s3')->get('client/saved-audiences/user_id_' . $user->id . '/' . $saved_filtered_audience_file->file_unique_name  . ".csv");
        } else {
          $saved_audience_file = \Storage::disk('local')->get('client/saved-audiences/user_id_' . $user->id . '/' . $saved_filtered_audience_file->file_unique_name  . ".csv");
        }
        
        $api = Api::init($app_id, $app_secret, $access_token);
        $api->setLogger(new CurlLogger());

        $fields = array(
        );

        $params = array(
          'name' => $saved_filtered_audience_file->file_name,
          'subtype' => 'CUSTOM',
          'description' => '',
          'customer_file_source' => 'BOTH_USER_AND_PARTNER_PROVIDED',
        );

        $result = json_encode((new AdAccount($id))->createCustomAudience(
          $fields,
          $params
        )->exportAllData(), JSON_PRETTY_PRINT);
        
        $result = json_decode($result, true);
        
        $saved_filtered_audience_file->update(["fb_audience_id", $result["id"]]);

        if(array_key_exists("id", $result)) {

          // File Data
          $csv_p = new \ParseCsv\Csv();
          $csv_p->encoding('UTF-8');
          $csv_p->delimiter = ";";
          //$csv_p->fields = ["FirstName","Surname","MobilePhone","Email", "IDNumber", "CustomVar1"];
          $csv_p->load_data(iconv("ISO-8859-1","UTF-8", $saved_audience_file));
          $csv_p->parse(iconv("ISO-8859-1","UTF-8", $saved_audience_file));
          
          $array_chunks = array_chunk($csv_p->data, 10000, false);

          $uploads = count($array_chunks);
          $records = count($csv_p->data);
         
          foreach($array_chunks as $chunk) {
            $data_array = array();

            foreach($chunk as $info) {
  
                $info["FirstName"] = normalizeAndHash($info["FirstName"]);
                $info["Surname"] = normalizeAndHash($info["Surname"]);
                $info["MobilePhone"] = normalizeAndHash(substr($info["MobilePhone"], 1));
                $info["Email"] = normalizeAndHash($info["Email"]);
  
                array_push($data_array, array_slice(array_values($info), 0, 4));
              
            }
  
            $fields_users = array(
            );
            $params_users = array(
              'payload' => array('schema' => array("FN", "LN", "PHONE", "EMAIL"),'data' => $data_array),
            );
  
            $result_users = json_encode((new CustomAudience($result["id"]))->createUser(
              $fields_users,
              $params_users
            )->exportAllData(), JSON_PRETTY_PRINT);

          }

          return response()->json(array("success" => "Process Complete", "message" => "Upload has successfully completed."));

        } else {

          return response()->json(array("error" => "Synch Error", "message" => "There's is an issue with the synced account. Either the ad account ID is incorrect or the account is not linked with a business."));
        }

      } else {
        return response()->json(array("error" => "File not found.", "message" => "The requested file could not be found on the server."));
      }

    }
    
}
