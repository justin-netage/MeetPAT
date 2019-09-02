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
        if(\Auth::user()->admin) {
            return redirect()->to('/meetpat-admin');
        } else if(\Auth::user()->client->active) {
            return view('client.main');
        } else {
            abort(401);
        }
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
                    \Session::flash('error', 'There was a problem linking your account please contact MeetPAT for assistance.');
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

            // \Session::flash('success', 'Your account has been authorized successfully.');

        } else {
            return response()->json(['ERROR' => 'Auth Token Faild To Generated', 'message' => 'An Error has occured please contact MeetPAT for assistance.']);

            // \Session::flash('error', 'An error occured. Check authorization code or contact MeetPAT for assistance.');
        }

        return response()->json(['SUCCESS' => 'Auth Token Generated', 'message' => 'Your account has been synced successfully.']);
        // return redirect("/meetpat-client");

    }

    public function add_facebook_account_id(Request $request)
    {
        // Remove session to prevent errors with facebook account sync.
        if($request->session()->has('facebook_access_token')) {
            $request->session()->forget('facebook_access_token');
        }

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

        return redirect()->to('/meetpat-client/sync/facebook');
    }

    public function upload_customers_handle(Request $request)
    {

      $validator = \Validator::make($request->all(), [
        'audience_name' => 'required|max:255|min:2',
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
            $file_exists = \Storage::disk('s3')->exists('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        } else {
            $file_exists = \Storage::disk('local')->exists('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        }

        if($file_exists) {
       
            $audience_file = \MeetPAT\AudienceFile::create(['user_id' => $request->user_id, 'audience_name' => $request->audience_name . " - " . time(), 'file_unique_name' => $request->file_id, 'file_source_origin' => $request->file_source_origin]);
  
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
        $user = \MeetPat\AudienceFile::find($job_que->file_id);

        if(env('APP_ENV') == 'production') {
            $actual_file = \Storage::disk('s3')->get('client/client-records/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        } else {
            $actual_file = \Storage::disk('local')->get('client/client-records/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        }

        $array = array_map("str_getcsv", explode("\n", $actual_file));
                    
        return response()->json(200);
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
            $file_exists = \Storage::disk('s3')->exists('client/client-records/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        } else {
            $file_exists = \Storage::disk('local')->exists('client/client-records/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        }        

        $actual_file = null;

        if(env('APP_ENV') == 'production' and $file_exists) {
            $actual_file = \Storage::disk('s3')->get('client/client-records/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
        } else if (env('APP_ENV') == 'local' and $file_exists) {
            $actual_file = \Storage::disk('local')->get('client/client-records/user_id_' . $file_info->user_id . '/' . $file_info->file_unique_name  . ".csv");
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
            if(preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/', $member[0])) {

                $firstName = $member[2];
                $lastName = $member[3];
                
                $memberByEmail = new Member();
                $memberByEmail->setHashedEmail(normalizeAndHash($member[0]));

                // if($member[2]) {
                //     $memberByEmail->setHashedFirstName(normalizeAndHash($firstName));
                // }
                // if($member[3]) {
                //     $memberByEmail->setHashedLastName(normalizeAndHash($lastName));
                // }

                if($member[1] and preg_match('/^\+27\d{9}$/', $member[1])) {
                    $memberByEmail->setHashedPhoneNumber(normalizeAndHash($member[1]));
                } else if(strlen($member[1]) == 10 and $member[1][0] == '0') {
                    $fixed_number = '+27' . substr($member[1], 1);
                    $memberByEmail->setHashedPhoneNumber(normalizeAndHash($fixed_number));
                } else if (strlen($member[1]) == 9) {
                    $fixed_number = '+27' . $member[1];
                    if(strlen($fixed_number) == 12) {
                        $memberByEmail->setHashedPhoneNumber(normalizeAndHash($fixed_number));
                    }
                }

                $members[] = $memberByEmail;
            }

        }

        // Add members to the operand and add the operation to the list.
        $operand->setMembersList($members);
        $mutateMembersOperation->setOperand($operand);
        $mutateMembersOperation->setOperator(Operator::ADD);
        $mutateMembersOperations[] = $mutateMembersOperation;

        // Add members to the user list based on email addresses.
        $result = $userListService->mutateMembers($mutateMembersOperations);
          
        $job_que->delete();

        return response()->json(200);
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
        function to_csv_line( $array ) {
            $temp = array();
            foreach( $array as $elt ) {
              $temp[] = addslashes( $elt );
            }
           
            $string = implode( ',', $temp ) . "\n";
           
            return $string;
           }
    
        function to_csv( $array ) {
            $csv;
            
            ## Grab the first element to build the header
            $arr = array_pop( $array );
            $temp = array();
            foreach( $arr as $key => $data ) {
                $temp[] = $key;
            }
            $csv = implode( ',', $temp ) . "\n";
            
            ## Add the data from the first element
            $csv .= to_csv_line( $arr );
            
            ## Add the data for the rest
            foreach( $array as $arr ) {   
                $csv .= to_csv_line( $arr );
            }
            
            return $csv;
        }

        $csv_file = $request->file('audience_file');
        $fileName = uniqid();
        $path = $_FILES['audience_file']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_content = file_get_contents($csv_file);
        $firstColumn = null;
        $client_uploads = \MeetPAT\ClientUploads::where(['user_id' => $request->user_id])->first();
        $uploads_left = 10000;

        if($client_uploads)
        {
            $uploads_left = $client_uploads->upload_limit - $client_uploads->uploads;
        }

        function readCSV($csvFile, $delimiter=",") {
            $file_handle = fopen($csvFile, 'r');
            while (!feof($file_handle) ) {
                $line_of_text[] = fgetcsv($file_handle, 0, $delimiter);
            }
            fclose($file_handle);
            return $line_of_text;
        }
           
        if($ext == 'csv') {
            $csv = readCSV($request->file('audience_file')); 
            if($csv[0] == ["FirstName","Surname","MobilePhone","Email", "IDNumber"]) {

                if(count($csv) > $uploads_left + 1) {
                    return response()->json(["status" => 500, "error" => "Your file contains more contacts than you have available for upload. You have <b>" . $uploads_left . "</b> uploads available. To increase your upload limit please contact your reseller."]);
                }

                if(env('APP_ENV') == 'production')
                {
                    $directory_used = \Storage::disk('s3')->makeDirectory('client/client-records/');
                    $file_uploaded = \Storage::disk('s3')->put('client/client-records/user_id_' . $request->user_id . '/' . $fileName  . ".csv", fopen($csv_file, 'r+'));
        
                } else {
                    $directory_used = \Storage::disk('local')->makeDirectory('client/client-records/');
                    $file_uploaded = \Storage::disk('local')->put('client/client-records/user_id_' . $request->user_id . '/' . $fileName  . ".csv", fopen($csv_file, 'r+'));
                }
            } else {
                $csv_array = readCSV($request->file('audience_file'), ";");

                if(similar_text("FirstName", $csv_array[0][0]) >= 5
                    and similar_text("Surname", $csv_array[0][1]) >= 5
                    and similar_text("MobilePhone", $csv_array[0][2]) >= 5
                    and similar_text("Email", $csv_array[0][3]) >= 5
                    and similar_text("IDNumber", $csv_array[0][4]) >= 5)
                {
                    //$parser = new \CsvParser\Parser(';', "'", "\n");
                    $csv_p = new \ParseCsv\Csv();
                    $csv_p->delimiter = ";";
                    $csv_p->fields = ["FirstName","Surname","MobilePhone","Email", "IDNumber"];
                    $csv_p->load_data($request->file('audience_file'));
                    $csv_p->parse($request->file('audience_file'));

                    $csv_str = to_csv($csv_p->data);
                    
                    // $csv = $parser->fromString($file_content);
                    // $parser->fieldDelimiter = ",";
                    // $parser->fieldEnclosure = "";
                    // $csv_str = $parser->toString($csv);

                    if(count($csv_array) > $uploads_left + 1) {
                        return response()->json(["status" => 500, "error" => "Your file contains more contacts than you have available for upload. You have <b>" . $uploads_left . "</b> uploads available.  To increase your upload limit please contact your reseller."]);
                    }
    
                    if(env('APP_ENV') == 'production')
                    {
                        $directory_used = \Storage::disk('s3')->makeDirectory('client/client-records/');
                        $file_uploaded = \Storage::disk('s3')->put('client/client-records/user_id_' . $request->user_id . '/' . $fileName  . ".csv", $csv_str);
            
                    } else {
                        $directory_used = \Storage::disk('local')->makeDirectory('client/client-records/');
                        $file_uploaded = \Storage::disk('local')->put('client/client-records/user_id_' . $request->user_id . '/' . $fileName  . ".csv", $csv_str);
                    }
                } else {
                    return response()->json(["status" => 500, "error" => "CSV File does not match template."]);
                }
                
            }

        } else {
            return response()->json(["status" => 500]);
        }
        
        return response()->json(["status" => 200,"file_id" => $fileName]);

    }
    public function handle_delete_upload(Request $request)
    {
        $file_exists = null;

        if(env('APP_ENV') == 'production') {
            $file_exists = \Storage::disk('s3')->exists('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        } else {
            $file_exists = \Storage::disk('local')->exists('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        }

        if($file_exists) {
            if(env('APP_ENV') == 'production') {
                $file_exists = \Storage::disk('s3')->delete('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
            } else {
                $file_exists = \Storage::disk('local')->delete('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
            }
        } else {
            return response(500);
        }

        // return response('File: '. $request->file_id .' -> has been removed');
        return response(200);

    }

    public function create_filtered_audience(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'number_of_contacts' => 'required'
        ]);

        $new_filtered_list = \MeetPAT\UserFilteredAudience::create([
            'user_id' => $request->user_id,
            'number_of_contacts' => $request->number_of_contacts,
            'selected_provinces' => implode($request->provinceContacts),
            'selected_areas' => implode($request->areaContacts),
            'selected_ages' => implode($request->AgeContacts) ,
            'selected_genders' => implode($request->GenderContacts) ,
            'selected_population_groups' => implode($request->populationContacts) ,
            'selected_generations' => implode($request->generationContacts) ,
            'selected_citizens_vs_residents' => implode($request->citizenVsResidentsContacts) ,
            'selected_marital_statuses' => implode($request->maritalStatusContacts) ,
            'selected_home_owners' => implode($request->homeOwnerContacts) ,
            'selected_risk_categories' => implode($request->riskCategoryContacts) ,
            'selected_household_incomes' => implode($request->houseHoldIncomeContacts) ,
            'selected_directors' => implode($request->directorsContacts) ,
        ]); 
        

        return redirect()->to('/meetpat-client/filtered-audience-form/' . $new_filtered_list->user_id . '/' . $new_filtered_list->id);
    }

    public function filtered_audience_form($user_id, $filtered_list_id) {
        $user = \Auth::user();
        $has_google_adwords_acc = $user->google_ad_account;
        $has_facebook_ad_acc = $user->facebook_ad_account;

        //\MeetPAT\UserFilteredAudience::where('user_id')->truncate();
        $filtered_list = \MeetPAT\UserFilteredAudience::find($filtered_list_id);
        $filters_array = [];
        
        if($filtered_list->selected_provinces) {
            $filters_array["selected_provinces"] = $filtered_list->selected_provinces;
        }

        if($filtered_list->selected_areas) {
            $filters_array["selected_areas"] = $filtered_list->selected_areas;
        }
        
        if($filtered_list->selected_ages) {
            $filters_array["selected_ages"] = $filtered_list->selected_ages;
        }

        if($filtered_list->selected_genders) {
            $filters_array["selected_genders"] = $filtered_list->selected_genders;
        }

        if($filtered_list->selected_population_groups) {
            $filters_array["selected_population_groups"] = $filtered_list->selected_population_groups;
        }

        if($filtered_list->selected_generations) {
            $filters_array["selected_generations"] = $filtered_list->selected_generations;
        }

        if($filtered_list->selected_citizens_vs_residents) {
            $filters_array["selected_citizens_vs_residents"] = $filtered_list->selected_citizens_vs_residents;
        }

        if($filtered_list->selected_marital_statuses) {
            $filters_array["selected_marital_statuses"] = $filtered_list->selected_marital_statuses;
        }

        if($filtered_list->selected_home_owners) {
            $filters_array["selected_home_owners"] = $filtered_list->selected_home_owners;
        }

        if($filtered_list->selected_risk_categories) {
            $filters_array["selected_risk_categories"] = $filtered_list->selected_risk_categories;
        }

        if($filtered_list->selected_household_incomes) {
            $filters_array["selected_household_incomes"] = $filtered_list->selected_household_incomes;
        }

        if($filtered_list->selected_directors) {
                $filters_array["selected_directors"] = $filtered_list->selected_directors;
        }

        if($user->id == $user_id and $filtered_list) {

            return view('client.filtered_audience.submit', [ 'user_id' => $user_id, 'filtered_list_id' => $filtered_list_id,
                                                             'has_google_adwords_acc' => $has_google_adwords_acc,
                                                             'has_facebook_ad_acc' => $has_facebook_ad_acc,
                                                             'filtered_list' => $filtered_list,'filters_array' => $filters_array]);
            // return response()->json($filters_array);
        } else {
            return view('client.filtered_audience.error');
        }
    }

    public function submit_filtered_audience(Request $request)  
    {
        $request->validate([
            'facebook_custom_audience' => 'required',
            'google_custom_audience' => 'required',
            'user_id' => 'required',
            'filtered_audience_id' => 'required',
            'audience_name' => 'required'
        ]);


    }

    // API Controllers

    // Submit Audience to job Que
    public function add_filtered_list_to_que(Request $request)
    {
        $request->validate([
            'platform' => 'required',
            'user_id' => 'required',
            'filtered_audience_id' => 'required',
            'audience_name' => ['required', 'regex:/^[a-zA-Z0-9\_ ]*$/']
        ]);

        $filtered_list_exists = \MeetPAT\UploadFilteredList::where(['filtered_list_id' => $request->filtered_audience_id]);
        $filtered_list_exists->delete();

        $filtered_list = \MeetPAT\UserFilteredAudience::find($request->filtered_audience_id);

        $new_filtered_list = \MeetPAT\UploadFilteredList::create(['user_id' => $request->user_id, 'platform' => $request->platform, 'status' => 'pending', 'filtered_list_id' => $request->filtered_audience_id, 'audience_name' => $request->audience_name]);
        
        return response()->json($new_filtered_list);
    }
    // Google
    // Run Job Que
    public function run_google_job_que(Request $request)
    {
        // Store to remove at end
        $job_que = \MeetPAT\UploadFilteredList::where([
            ['user_id', '=',  $request->user_id],
            ['platform', '=', 'google'],
            ['filtered_list_id', '=', $request->filtered_audience_id]
            ])->first();

        $user = \MeetPAT\User::find($request->user_id);
        $google_account = $user->google_ad_account;

        // store filtered list data from records database
        $filtered_list = \MeetPAT\UserFilteredAudience::find($request->filtered_audience_id);
        $filtered_list_name = \MeetPAT\UploadFilteredList::where(['filtered_list_id' => $request->filtered_audience_id, 'platform' => 'google'])->first()->audience_name;
        $records = \MeetPAT\BarkerStreetRecord::whereRaw("find_in_set('".$request->user_id."',affiliated_users)");

        // Filter By Provinces
        if($filtered_list->selected_provinces) {
            $records = $records->whereIn('Province', explode(",", $filtered_list->selected_provinces));
        }
        // Filter By Municipalities
        if($filtered_list->selected_municipalities) {
            $records = $records->whereIn('GreaterArea', explode(",", $filtered_list->selected_municipalities));
        }
        // Filter By Areas
        if($filtered_list->selected_areas) {
            $records = $records->whereIn('Area', explode(",", $filtered_list->selected_areas));
        }
        // Filter By Directorship
        if($filtered_list->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', explode(",", $filtered_list->selected_directors));
        }
        // Filter By Age Groups
        if($filtered_list->selected_ages) {
            $records = $records->whereIn('AgeGroup', explode(",", $filtered_list->selected_ages));
        }
        // Filter By Gender
        if($filtered_list->selected_genders) {
            $records = $records->whereIn('Gender', explode(",", $filtered_list->selected_genders));
        }
        // Filter By Population Group
        if($filtered_list->selected_population_groups) {
            $records = $records->whereIn('PopulationGroup', explode(",", $filtered_list->selected_population_groups));
        }
        // Filter By Generation Group
        if($filtered_list->selected_generations) {
            $records = $records->whereIn('GenerationGroup', explode(",", $filtered_list->selected_generations));
        }
        // Filter By Marital Status
        if($filtered_list->selected_marital_statuses) {
            $records = $records->whereIn('MaritalStatus', explode(",", $filtered_list->selected_marital_statuses));
        }
        // Filter By Home Owners
        if($filtered_list->selected_home_owners) {
            $records = $records->whereIn('HomeOwnerShipStatus', explode(",", $filtered_list->selected_home_owners));
        }  
        // Filter By Risk Categories
        if($filtered_list->selected_risk_categories) {
            $records = $records->whereIn('CreditRiskCategory', explode(",", $filtered_list->selected_risk_categories));
        }
        // Filter By Household Income
        if($filtered_list->selected_household_incomes) {
            $records = $records->whereIn('incomeBucket', explode(",", $filtered_list->selected_household_incomes));
        }    
        // Filter By directors
        if($filtered_list->selected_directors) {
            $records = $records->whereIn('DirectorshipStatus', explode(",", $filtered_list->selected_directors));
        }     
        // Filter By areas
        if($filtered_list->selected_areas) {
            $records = $records->whereIn('Area', explode(",", $filtered_list->selected_areas));
        }       
        // Filter By Citizens and residents
        if($filtered_list->selected_citizen_vs_residents) {
            if(in_array("citizen", $filtered_list->selected_citizen_vs_residents)) {
                $records = $records->where('Idn', '!=', '');
                

            } else if(in_array("resident", $filtered_list->selected_citizen_vs_residents)) {
                $records = $records->where('HasResidentialAddress', "true");

            }
        }

        $records = $records->get();

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
            $filtered_list_name
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

        foreach($records as $member)
        {
            // if(preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/', $member->email)) {

                // $addressInfo = new AddressInfo();
                // First and last name must be normalized and hashed.
                // $addressInfo->setHashedFirstName(normalizeAndHash($member->FirstName));
                // $addressInfo->setHashedLastName(normalizeAndHash($lastName->Surname));
                // // Country code and zip code are sent in plain text.
                // $addressInfo->setCountryCode('ZA');
                // $addressInfo->setZipCode($member->PostalAddress1PostalCode);
                        
                $memberByEmail = new Member();
                // $memberByEmail->setAddressInfo($addressInfo);
                $memberByEmail->setHashedEmail(normalizeAndHash($member->email));
                $memberByEmail->setHashedPhoneNumber(normalizeAndHash($member->MobilePhone1));

                // if(preg_match('/^\+27\d{9}$/', $member->MobilePhone1)) {
                //     $memberByEmail->setHashedPhoneNumber(normalizeAndHash($member->MobilePhone1));
                // } else if(strlen($member->MobilePhone1) == 10 and $member->MobilePhone1[0] == '0') {
                //     $fixed_number = '+27' . substr($member->MobilePhone1, 1);
                //     $memberByEmail->setHashedPhoneNumber(normalizeAndHash($fixed_number));
                // } else if (strlen($member->MobilePhone1) == 9) {
                //     $fixed_number = '+27' . $member->MobilePhone1;
                //     if(strlen($fixed_number) == 12) {
                //         $memberByEmail->setHashedPhoneNumber(normalizeAndHash($fixed_number));
                //     }
                // } else if(preg_match('/^27\d{9}$/', $member->MobilePhone1)) {
                //     $fixed_number = '+' . $member->MobilePhone1;
                //     $memberByEmail->setHashedPhoneNumber(normalizeAndHash($fixed_number));
                // }

                $members[] = $memberByEmail;
            // }

        }

        // Add members to the operand and add the operation to the list.
        $operand->setMembersList($members);
        $mutateMembersOperation->setOperand($operand);
        $mutateMembersOperation->setOperator(Operator::ADD);
        $mutateMembersOperations[] = $mutateMembersOperation;

        // Add members to the user list based on email addresses.
        $result = $userListService->mutateMembers($mutateMembersOperations);
          
        $job_que->delete();

        return response()->json(["result" => $members]);
    }
    // Facebook
    // Run Job Que
    public function run_facebook_job_que(Request $request)
    {
        
        return response()->json(['status' => 'done']);
    }    

    // Settings

    public function account_settings()
    {
        $user = \Auth::user();

        $has_business_details = $user->client_details;
        $has_facebook_ad_account = $user->facebook_ad_account;
        $has_google_ad_account = $user->google_ad_account;
        
        return view('client.dashboard.account_settings', ['has_facebook_ad_account' => $has_facebook_ad_account,
                                                          'has_google_ad_account' => $has_google_ad_account,
                                                          'has_business_details' => $has_business_details
                                                          ]);
    }

    public function save_settings(Request $request)
    {
        $user = \MeetPAT\User::find($request->user_id);
        $has_business_details = $user->client_details;
        $has_facebook_ad_acc = $user->facebook_ad_account;
        $has_google_ad_acc = $user->google_ad_account;
        
        if($has_business_details) {
            $has_business_details->update($request->all());
        } else {
            $has_business_details = \MeetPAT\MeetpatClientDetail::create($request->all());
        }

        if($request->facebook_acc_id != "false") 
        {
            if($has_facebook_ad_acc) 
            {
                $has_facebook_ad_acc->update(['ad_account_id' => $request->facebook_acc_id]);
            }
        }

        if($request->google_acc_id != "false") 
        {
            if($has_google_ad_acc)
            {
                $has_google_ad_acc->update(['ad_account_id' => $request->google_acc_id]);
            }
        }

        return response()->json($has_business_details);
    }

    public function disconnect_platform(Request $request)
    {   
        $user = \MeetPAT\User::find($request->user_id);
        $google_disconnected = 'false';
        $facebook_disconnected = 'false';

        if($request->platform == 'google')
        {
            $has_google_ad_account = $user->google_ad_account;
            if($has_google_ad_account) {
                $google_disconnected = $has_google_ad_account->delete();
            }

        } else if($request->platform == 'facebook') {
            $has_facebook_ad_account = $user->facebook_ad_account;   
            if($has_facebook_ad_account) {
                $facebook_disconnected = $has_facebook_ad_account->delete();
            }
         
        }

        return response()->json(['status' => '200', 'google_disconnected' => $google_disconnected, 'facebook_disconnected' => $facebook_disconnected]);
    }
}