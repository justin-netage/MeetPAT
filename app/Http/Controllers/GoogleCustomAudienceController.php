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


}
