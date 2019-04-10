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

// Disable Default registration page
Route::match(['get', 'post'], 'register', function(){
    return redirect(404);
});

// Administrator routes

Route::get('/meetpat-admin', 'AdministratorController@main')->name('meetpat-admin')->middleware('auth')->middleware('admin');

// Some User POST Routes are in the API routes file. ~/meetpat/routes/api.php
// TODO ->middleware('auth');
Route::get('/meetpat-admin/users', 'AdministratorController@users_view')->name('meetpat-users')->middleware('auth')->middleware('admin');
Route::get('/meetpat-admin/users/create', 'AdministratorController@create_user_view')->name('create-user')->middleware('auth')->middleware('admin');

Route::post('/meetpat-admin/users/create/save', 'AdministratorController@create_user')->name('create-user-save')->middleware('auth')->middleware('admin');

Route::get('/meetpat-admin/users/files/{user_id}', 'AdministratorController@display_user_files')->middleware('auth')->middleware('admin');
// MeetPAT Client Routes

Route::get('/meetpat-client', 'MeetpatClientController@main')->name('meetpat-client')->middleware('auth');

// Dashboard Home Links
Route::get('/meetpat-client/data-visualisation', 'DataVisualisationController@index')->name('meetpat-data-visualisation')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/tutorials', 'MeetpatClientController@tutorials')->name('meetpat-tutorials')->middleware('auth')->middleware('client');

// Account Sync Pages
Route::get('/meetpat-client/sync-platform', 'MeetpatClientController@sync_platform')->name('meetpat-client-sync')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/upload-clients', 'MeetpatClientController@upload_clients')->name('meetpat-client-upload')->middleware('auth')->middleware('client');

// Google Register Routes
Route::get('/register-google-ad-account', 'GoogleCustomerAudienceController@register_ad_account_id')->name('google-ad-account')->middleware('auth')->middleware('client');


Route::post('/google-authorization/authenticate-authorization-code', 'MeetpatClientController@authenticate_authorization_code')->name('authenticate-google-code')->middleware('client');
Route::post('/facebook-account-update/add-ad-account-id', 'MeetpatClientController@add_facebook_account_id')->name('add-account-id')->middleware('client');

// Upload pages
// Upload api handler /routes/api.php

// Download link for sample file Facebook Custom Audiences
Route::get('/meetpat-client/upload-clients/facebook-sample-audience', 'FacebookCustomerAudienceController@download_sample_file')->name('facebook-download-sample')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/upload-clients/google-sample-audience', 'GoogleCustomerAudienceController@download_sample_file')->name('google-download-sample')->middleware('auth')->middleware('client');

// Update account if token needs to be refreshed or id needs to be added
Route::get('/meetpat-client/sync/facebook', 'MeetpatClientController@sync_facebook')->name('sync-facebook')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/sync/google', 'MeetpatClientController@sync_google')->name('sync-google')->middleware('auth')->middleware('client');

Route::get('/meetpat-client/update/facebook', 'MeetpatClientController@update_facebook')->name('update-facebook')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/update/google', 'MeetpatClientController@update_google')->name('update-google')->middleware('auth')->middleware('client');

// Page to upload large file of client data

Route::get('/meetpat-client/upload-client-file-data', 'DataVisualisationController@large_data_upload_form')->name('upload-client-data')->middleware('auth')->middleware('client');

// Routes for user filtered customer audience

Route::post('/meetpat-client/create-selected-contacts', 'MeetpatClientController@create_filtered_audience')->name('sync-selected-contacts')->middleware('auth')->middleware('client');
Route::post('/meetpat-client/upload-audience-form', 'MeetpatClientController@submit_filtered_audience')->name('submit-filtered-audience')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/filtered-audience-form/{user_id}/{filtered_list_id}', 'MeetpatClientController@filtered_audience_form')->name('filtered-audience-form')->middleware('auth')->middleware('client');

// Account Settings

Route::get('/meetpat-client/settings', 'MeetpatClientController@account_settings')->name('account-settings')->middleware('auth')->middleware('client');