<?php

namespace MeetPAT\Http\Controllers\Auth;

use Illuminate\Http\Request;
use MeetPAT\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }
    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */

    public function handleProviderCallback()
    {
        $user = Socialite::driver('google')->user();
        //$has_google_account = $user->ad_word_account();

        // if($has_google_account) {
        //     $has_google_account->update(['access_token' => $user->token]);
        // } else {
            \MeetPAT\GoogleAdwordsAccount::create(['access_token' => $user->token, 'user_id' => $user->id, 'ad_account_id' => null]);
        // }
    }

    public function google_account_login()
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


        return view('auth.google_account_login', ['auth_uri' => $auth_uri]);
    }

}
