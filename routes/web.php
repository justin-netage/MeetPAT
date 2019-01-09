<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/react', function () {
    return view('react');
});

// Client Communication Pages

Route::get('/contact', 'ContactController@contact')->name('contact');
Route::get('/apply', 'ContactController@apply')->name('apply');

// Information Pages

Route::get('/how-it-works', 'InformationController@how_it_works')->name('how-it-works');
Route::get('/benefits', 'InformationController@benefits')->name('benefits');
Route::get('/insights', 'InformationController@insights')->name('insights');
Route::get('/onboarding', 'InformationController@onboarding')->name('onboarding');
Route::get('/pricing', 'InformationController@pricing')->name('pricing');

Auth::routes();

// Disable Default registration
// Route::match(['get', 'post'], 'register', function(){
//     return redirect(404);
// });

// Administrator routes

Route::get('/meetpat-admin', 'AdministratorController@main')->name('meetpat-admin')->middleware('auth')->middleware('admin');

// Some User POST Routes are in the API routes file. ~/meetpat/routes/api.php
// TODO ->middleware('auth');
Route::get('/meetpat-admin/users', 'AdministratorController@users_view')->name('meetpat-users')->middleware('auth')->middleware('admin');
Route::get('/meetpat-admin/users/create', 'AdministratorController@create_user_view')->name('create-user')->middleware('auth')->middleware('admin');

Route::post('/meetpat-admin/users/create/save', 'AdministratorController@create_user')->name('create-user-save')->middleware('auth')->middleware('admin');

// Route::get('/meetpat-admin/users/{user_id}/edit', 'AdministratorController@edit_user_view')->name('edit-user')->middleware('auth')->middleware('admin');
// Route::get('/meetpat-admin/users/{user_id}/delete', 'AdministratorController@delete_view')->name('delete-user')->middleware('auth')->middleware('admin');

// MeetPAT Client Routes

Route::get('/meetpat-client', 'MeetpatClientController@main')->name('meetpat-client')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/sync-platform', 'MeetpatClientController@sync_platform')->name('meetpat-client-sync')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/upload-clients', 'MeetpatClientController@upload_clients')->name('meetpat-client-upload')->middleware('auth')->middleware('client');


// Facebook Login Routes

Route::get('/fb-callback', function() {
        $fb = new Facebook\Facebook([
        'app_id' => env('FACEBOOK_APP_ID'),
        'app_secret' => env('FACEBOOK_APP_SECRET'),
        'default_graph_version' => 'v2.10',
        ]);
      
        $helper = $fb->getRedirectLoginHelper();
        
        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('https://infinite-coast-17182.herokuapp.com/fb-callback', $permissions);
        
        echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
});

Route::get('/facebook-login', function() {
    $fb = new Facebook\Facebook([
        'app_id' => env('FACEBOOK_APP_ID'),
        'app_secret' => env('FACEBOOK_APP_SECRET'),
        'default_graph_version' => 'v2.10',
        ]);
      
      $helper = $fb->getRedirectLoginHelper();
      
      try {
        $accessToken = $helper->getAccessToken();
      } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
      } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }
      
      if (! isset($accessToken)) {
        if ($helper->getError()) {
          header('HTTP/1.0 401 Unauthorized');
          echo "Error: " . $helper->getError() . "\n";
          echo "Error Code: " . $helper->getErrorCode() . "\n";
          echo "Error Reason: " . $helper->getErrorReason() . "\n";
          echo "Error Description: " . $helper->getErrorDescription() . "\n";
        } else {
          header('HTTP/1.0 400 Bad Request');
          echo 'Bad request';
        }
        exit;
      }
      
      // Logged in
      echo '<h3>Access Token</h3>';
      var_dump($accessToken->getValue());
      
      // The OAuth 2.0 client handler helps us manage access tokens
      $oAuth2Client = $fb->getOAuth2Client();
      
      // Get the access token metadata from /debug_token
      $tokenMetadata = $oAuth2Client->debugToken($accessToken);
      echo '<h3>Metadata</h3>';
      var_dump($tokenMetadata);
      
      // Validation (these will throw FacebookSDKException's when they fail)
      $tokenMetadata->validateAppId($config['app_id']);
      // If you know the user ID this access token belongs to, you can validate it here
      //$tokenMetadata->validateUserId('123');
      $tokenMetadata->validateExpiration();
      
      if (! $accessToken->isLongLived()) {
        // Exchanges a short-lived access token for a long-lived one
        try {
          $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
          echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
          exit;
        }
      
        echo '<h3>Long-lived</h3>';
        var_dump($accessToken->getValue());
      }
      
      $_SESSION['fb_access_token'] = (string) $accessToken;    
});



