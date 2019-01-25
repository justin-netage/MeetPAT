<?php

namespace MeetPAT\Http\Controllers\Auth;

use Illuminate\Http\Request;
use MeetPAT\Http\Controllers\Controller;
use Socialite;

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

}
