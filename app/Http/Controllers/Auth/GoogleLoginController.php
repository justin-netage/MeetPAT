<?php

namespace MeetPAT\Http\Controllers\Auth;

use Illuminate\Http\Request;
use MeetPAT\Http\Controllers\Controller;

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */

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

}
