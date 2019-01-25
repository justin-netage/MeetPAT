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

Route::get('/register-facebook-ad-account', 'FacebookCustomerAudienceController@register_ad_account_id')->name('facebook-ad-account')->middleware('auth')->middleware('client');

// Google Login Routes
Route::get('/register-google-ad-account', 'GoogleCustomerAudienceController@register_ad_account_id')->name('google-ad-account')->middleware('auth')->middleware('client');
Route::get('login/google', 'Auth\GoogleLoginController@redirectToProvider');
Route::get('login/google/callback', 'Auth\GoogleLoginController@handleProviderCallback');

// Facebook upload routes

// Upload pages
Route::get('/meetpat-client/upload-clients/facebook', 'FacebookCustomerAudienceController@upload_facebook_customers')->name('facebook-upload-customers')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/upload-clients/google', 'GoogleCustomerAudienceController@upload_google_customers')->name('google-upload-customers')->middleware('auth')->middleware('client');

// Upload api handler /routes/api.php

// Download link for sample file Facebook Custom Audiences
Route::get('/meetpat-client/upload-clients/facebook-sample-audience', 'FacebookCustomerAudienceController@download_sample_file')->name('facebook-download-sample')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/upload-clients/google-sample-audience', 'GoogleCustomerAudienceController@download_sample_file')->name('google-download-sample')->middleware('auth')->middleware('client');

