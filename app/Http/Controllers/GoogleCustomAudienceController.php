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

class GoogleCustomAudienceController extends Controller
{
    //
    public function create_custom_audience(Request $request) {

        $user = \MeetPAT\User::find($request->user_id);

        if($user->google_ad_account and $user->google_ad_account->ad_account_id
            and $user->google_ad_account->access_token) {
            
            $saved_filtered_audience_file = \MeetPAT\SavedFilteredAudienceFile::find($request->filtered_audience_id);
            $ad_acc = $user->google_ad_account;
            $file_exists = \Storage::disk('s3')->exists('client/saved-audiences/user_id_' . $user->id . '/' . $saved_filtered_audience_file->file_unique_name  . ".csv");

            if($file_exists) 
            {
                $oAuth2Credential = (new OAuth2TokenBuilder())
                    ->withClientId(env('GOOGLE_CLIENT_ID'))
                    ->withClientSecret(env('GOOGLE_CLIENT_SECRET'))
                    ->withRefreshToken($ad_acc->access_token)
                    ->build();

                // Construct an API session configured from the OAuth2 credentials above.
                $session = (new AdWordsSessionBuilder())
                    ->withDeveloperToken(env('GOOGLE_MCC_DEVELOPER_TOKEN'))
                    ->withOAuth2Credential($oAuth2Credential)
                    ->withClientCustomerId($ad_acc->ad_account_id)
                    ->build();

                    $adWordsServices = new AdWordsServices();
        
                    $userListService = $adWordsServices->get($session, AdwordsUserListService::class);
            
                    // Create a CRM based iser list.
                    $userList = new CrmBasedUserList();
                    $userList->setName(
                        $saved_filtered_audience_file->file_name
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
                    
                    $new_custom_audience = \MeetPAT\GoogleAudienceUploadQueue::create(
                        array('user_id' => $user->id,
                              'custom_audience_id' => $userList->getId(),
                              'saved_audience_file_id' => $saved_filtered_audience_file->id,
                              'status' => 'pending'
                              )
                    );

                return response()->json(array("status" => "success", "status-text" => "Job Queued", "message" => "Upload has been queued to process."));
            } else {

                return response()->json(array("status" => "error", "status-text" => "File not found.", "message" => "The requested file could not be found on the server."));
            }
            
        } else {
            return response()->json(array("status" => "error", "status-text" => "Google Ad Account", "Google Ad Account has not been linked.")); 
        }
    }

}
