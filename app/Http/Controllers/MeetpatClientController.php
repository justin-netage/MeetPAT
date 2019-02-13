<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201809\cm\Operator;
use Google\AdsApi\AdWords\v201809\rm\AddressInfo;
use Google\AdsApi\AdWords\v201809\rm\AdwordsUserListService;
use Google\AdsApi\AdWords\v201809\rm\CrmBasedUserList;
use Google\AdsApi\AdWords\v201809\rm\CustomerMatchUploadKeyType;
use Google\AdsApi\AdWords\v201809\rm\Member;
use Google\AdsApi\AdWords\v201809\rm\MutateMembersOperand;
use Google\AdsApi\AdWords\v201809\rm\MutateMembersOperation;
use Google\AdsApi\AdWords\v201809\rm\UserListOperation;
use Google\AdsApi\Common\OAuth2TokenBuilder;

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
        $env = env('APP_ENV');

        $has_google_adwords_acc = $user->google_ad_account;
        $has_facebook_ad_acc = $user->facebook_ad_account;

        return view('client.dashboard.upload_clients', ['has_google_adwords_acc' => $has_google_adwords_acc, 'has_facebook_ad_acc' => $has_facebook_ad_acc, 'env' => $env]);
    }

    // Update synced accounts

    public function sync_facebook()
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

                } else {
                    \Session::flash('error', 'There was a problem linking your account please contact MeetPAT for asssistance.');
                }
            }

          } else {

            $permissions = ['ads_management'];
            $loginUrl = $helper->getReAuthenticationUrl('https://infinite-coast-17182.herokuapp.com/meetpat-client/sync/facebook', $permissions);
            // echo '<a href="' . $loginUrl . '">Log in with Facebook</a>';
          }

        return view('client.dashboard.sync_facebook_acc', ['login_url' => $loginUrl]);
    }


    public function sync_google()
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

        return view('client.dashboard.sync_google_acc', ['auth_uri' => $auth_uri]);
    }


    public function authenticate_authorization_code(Request $request)
    {
        $validatedData = $request->validate([
            'adwords_id' => 'required',
            'auth_code' => 'required',
            'user_id' => 'required',
        ]);

        $PRODUCTS = [
            ['AdWords API', 'https://www.googleapis.com/auth/adwords'],
            ['Ad Manager API', 'https://www.googleapis.com/auth/dfp'],
            ['AdWords API and Ad Manager API', 'https://www.googleapis.com/auth/adwords' . ' '
                . 'https://www.googleapis.com/auth/dfp']
        ];

        //$scopes = $PRODUCTS[2][1] . ' ' . trim(fgets($stdin));

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
                \MeetPAT\GoogleAdwordsAccount::create(['user_id' => $user->id, 'ad_account_id' => $request->adwords_id, 'access_token' => $authToken['refresh_token'] ]);
            } else {
                $has_ad_account->update(['ad_account_id' => $request->adwords_id, 'access_token' => $authToken['refresh_token'] ]);
            }

            \Session::flash('success', 'Your account has been authorized successfully.');

        } else {
            \Session::flash('error', 'An error occured. Check authorization code or contact MeetPAT for assistance.');
        }

        return redirect("/meetpat-client");

    }

    public function add_facebook_account_id(Request $request)
    {
        $validatedData = $request->validate([
            'ad_account_id' => 'required|min:10',
        ]);

        $user = \Auth::user();
        $facebook_account = $user->facebook_ad_account;

        if($facebook_account) {

            $facebook_account->update(['ad_account_id' => $request->ad_account_id]);

            \Session::flash('success', 'Ad Account ID updated successfully');

        } else {
            \Session::flash('error', 'Please authenticate your facebook ad account first.');

        }

        return back();
    }

    public function upload_customers_handle(Request $request)
    {

      $validator = \Validator::make($request->all(), [
        'audience_name' => 'required|unique:audience_files,audience_name,' . $request->user_id,
        'user_id' => 'required',
        'file_source_origin' => 'required',
        'file_id' => 'required',
    ]);
    
    if ($validator->fails())
    {
        return response()->json(['errors'=>$validator->errors()]);
    } else {

        $facebook_job = null;
        $google_job = null;
        $new_jobs = null;
        $file_exists = null;

        if(env('APP_ENV') == 'production') {
            $file_exists = Storage::disk('s3')->exists('client/custom-audience/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        } else {
            $file_exists = Storage::disk('local')->exists('client/custom-audience/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        }

        if($file_exists) {
       
            $audience_file = \MeetPAT\AudienceFile::create(['user_id' => $request->user_id, 'audience_name' => $request->audience_name, 'file_unique_name' => $request->file_id, 'file_source_origin' => $request->file_source_origin]);
  
          if($request->facebook_custom_audience) {
            $facebook_job = \MeetPAT\UploadJobQue::create(['user_id' => $request->user_id, 'unique_id' => $request->file_id, 'platform' => 'facebook', 'status' => 'pending', 'file_id' => $audience_file->id]);
          }
  
          if($request->google_custom_audience) {
            $google_job = \MeetPAT\UploadJobQue::create(['user_id' => $request->user_id, 'unique_id' => $request->file_id, 'platform' => 'google', 'status' => 'pending', 'file_id' => $audience_file->id]);
          }

        //   function readCSV($csvFile) {
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

        $new_jobs = \MeetPAT\UploadJobQue::where('unique_id', $request->file_id)->get();
  
        } else {
            return response("file does not exist :(");
        }

        return response()->json($new_jobs);
    
        }

    }

    public function facebook_custom_audience_handler(Request $request)
    {
        $job_que = \MeetPAT\UploadJobQue::where([
            ['unique_id', '=',  $request->unique_id],
            ['platform', '=', 'facebook'],
            ])->first();

        $file_info = \MeetPAT\AudienceFile::find($job_que->file_id);

        if(env('APP_ENV') == 'production') {
            $actual_file = \Storage::disk('s3')->get('client/custom-audience/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        } else {
            $actual_file = \Storage::disk('local')->get('client/custom-audience/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        }

        $array = array_map("str_getcsv", explode("\n", $actual_file));
                    
        return response()->json($array);
    }

    public function google_custom_audience_handler(Request $request)
    {
        
        $job_que = \MeetPAT\UploadJobQue::where([
            ['unique_id', '=',  $request->unique_id],
            ['platform', '=', 'google'],
            ])->first();

        $file_info = \MeetPAT\AudienceFile::find($job_que->file_id);
        $user = \MeetPAT\User::find($file_info->user_id);
        $google_account = $user->google_ad_account;

        $file_exists = null;

        if(env('APP_ENV') == 'production') {
            $file_exists = \Storage::disk('s3')->exists('client/custom-audience/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        } else {
            $file_exists = \Storage::disk('local')->exists('client/custom-audience/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        }        

        $actual_file = null;

        if(env('APP_ENV') == 'production' and $file_exists) {
            $actual_file = \Storage::disk('s3')->get('client/custom-audience/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        } else if (env('APP_ENV') == 'local' and $file_exists) {
            $actual_file = \Storage::disk('local')->get('client/custom-audience/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        } else {
            return response("file not found.");
        }

        $array = array_map("str_getcsv", explode("\n", $actual_file));
        $custom_audience_array = [];

        foreach($array as $member) 
        {
            if($member) {
                array_push($custom_audience_array, $member);
            }
        }
        
        unset($custom_audience_array[0]);
        $array_length = count($custom_audience_array);
        unset($custom_audience_array[$array_length]);
        
        $file_info = \MeetPAT\AudienceFile::find($job_que->file_id);

        // hash function
        function normalizeAndHash($value)
        {
            return hash('sha256', strtolower(trim($value)));
        }

        $oAuth2Credential = (new OAuth2TokenBuilder())
        ->withClientId(env('GOOGLE_CLIENT_ID'))
        ->withClientSecret(env('GOOGLE_CLIENT_SECRET'))
        ->withRefreshToken($google_account->access_token)
        ->build();

        // Construct an API session configured from the OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())
            ->withDeveloperToken(env('GOOGLE_MCC_DEVELOPER_TOKEN'))
            ->withOAuth2Credential($oAuth2Credential)
            ->withClientCustomerId($google_account->ad_account_id)
            ->build();

        $adWordsServices = new AdWordsServices();
        
        $userListService = $adWordsServices->get($session, AdwordsUserListService::class);

        // Create a CRM based iser list.
        $userList = new CrmBasedUserList();
        $userList->setName(
            $file_info->audience_name
        );
        $userList->setDescription(
            'Audience uploaded from MeetPAT.'
        );

        // Set life span to unlimitted (10000)
        $userList->setMembershipLifeSpan(10000);
        $userList->setUploadKeyType(CustomerMatchUploadKeyType::CONTACT_INFO);

        // Create a user list operation and add it to the list.
        $operations = [];
        $operation = new UserListOperation();
        $operation->setOperand($userList);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;

        // Create the user list on the server and print out some information.
        $userList = $userListService->mutate($operations)->getValue()[0];

        // Create operation to add members to the user list based on email
        // addresses.
        $mutateMembersOperations = [];
        $mutateMembersOperation = new MutateMembersOperation();
        $operand = new MutateMembersOperand();
        $operand->setUserListId($userList->getId());

        $members = [];
        //Hash normalized email address based on SHA-256 hashing

        foreach($custom_audience_array as $member)
        {
            $memberByEmail = new Member();
            $memberByEmail->setHashedEmail(normalizeAndHash($member[0]));
            // $memberByEmail->setHashedPhoneNumber(normalizeAndHash($member[0]));
            // $memberByEmail->setFirstName($member[0]);
            // $memberByEmail->setLastName($member[0]);
            
            $members[] = $memberByEmail;
        }

        // Add members to the operand and add the operation to the list.
        $operand->setMembersList($members);
        $mutateMembersOperation->setOperand($operand);
        $mutateMembersOperation->setOperator(Operator::ADD);
        $mutateMembersOperations[] = $mutateMembersOperation;

        // Add members to the user list based on email addresses.
        $result = $userListService->mutateMembers($mutateMembersOperations);
          
        $job_que->delete();

        return response()->json($members);
    }

    public function update_facebook()
    {
        return view('client.dashboard.update_facebook_acc', []);
    } 


    public function update_google()
    {
        return view('client.dashboard.update_google_acc', []);
    }


    public function handle_upload(Request $request)
    {
        $csv_file = $request->file('audience_file');
        $fileName = uniqid();
        if(env('APP_ENV') == 'production')
        {
            $directory_used = \Storage::disk('s3')->makeDirectory('client/custom-audience/');
            $file_uploaded = \Storage::disk('s3')->put('client/custom-audience/user_id_' . $request->user_id . '/' . $fileName  . ".csv", fopen($csv_file, 'r+'));

        } else {
            $directory_used = \Storage::disk('local')->makeDirectory('client/custom-audience/');
            $file_uploaded = \Storage::disk('local')->put('client/custom-audience/user_id_' . $request->user_id . '/' . $fileName  . ".csv", fopen($csv_file, 'r+'));
        }

        return response($fileName);

    }
    public function handle_delete_upload(Request $request)
    {
        $file_exists = null;

        if(env('APP_ENV') == 'production') {
            $file_exists = \Storage::disk('s3')->exists('client/custom-audience/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        } else {
            $file_exists = \Storage::disk('local')->exists('client/custom-audience/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        }

        if($file_exists) {
            if(env('APP_ENV') == 'production') {
                $file_exists = \Storage::disk('s3')->delete('client/custom-audience/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
            } else {
                $file_exists = \Storage::disk('local')->delete('client/custom-audience/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
            }
        } else {
            return response(500);
        }

        // return response('File: '. $request->file_id .' -> has been removed');
        return response(200);

    }
}



