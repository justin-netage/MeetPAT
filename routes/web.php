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
    return view('client.main');
})->middleware('auth');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/react', function () {
    return view('react');
});

// Legal

Route::get('/terms', function() {
    return view('legal.terms');
});

Route::get('/privacy-policy', function() {
    return view('legal.privacy_policy');
});

// Client Communication Pages

Route::get('/contact', 'ContactController@contact')->name('contact')->middleware('auth');
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

/** BEGIN Administrator routes */ 

Route::get('/meetpat-admin', 'AdministratorController@main')->name('meetpat-admin')->middleware('auth')->middleware('admin');

// Some User POST Routes are in the API routes file. ~/meetpat/routes/api.php
//Route::get('/meetpat-admin/users', 'AdministratorController@users_view')->name('meetpat-users')->middleware('auth')->middleware('admin');
//New User Table using tabulator
Route::get('/meetpat-admin/users', 'AdministratorController@users_view')->name('meetpat-users')->middleware('auth')->middleware('admin');
Route::get('/meetpat-admin/clients/create', 'AdministratorController@create_client_view')->name('create-user')->middleware('auth')->middleware('admin');
Route::post('/meetpat-admin/users/create/save', 'AdministratorController@create_client')->name('create-user-save')->middleware('auth')->middleware('admin');
Route::get('/meetpat-admin/users/files/{user_id}', 'AdministratorController@display_user_files')->middleware('auth')->middleware('admin');
Route::get('/meetpat-admin/enriched-data-tracking', 'AdministratorController@enriched_data_tracking')->name('enriched-data-tracking')->middleware('auth')->middleware('admin');
Route::get('/meetpat-admin/running-jobs', 'AdministratorController@running_jobs')->name('running-jobs')->middleware('auth')->middleware('admin');

Route::get('meetpat-admin/clients', 'AdministratorController@clients_view')->name('meetpat-clients')->middleware('auth')->middleware('admin');

// TODO Create Reseller user form

Route::get('/meetpat-admin/resellers', 'AdministratorController@resellers_view')->name('meetpat-resellers')->middleware('auth')->middleware('admin');
Route::get('/meetpat-admin/resellers/create', 'AdministratorController@create_reseller_view')->name('create-reseller')->middleware('auth')->middleware('admin');
Route::post('/meetpat-admin/resellers/create/save', 'AdministratorController@create_reseller')->name('create-reseller-save')->middleware('auth')->middleware('admin');

/** END Administrator routes */

/** BEGIN MeetPAT Reseller Routes */
Route::get('/meetpat-reseller', 'ResellerController@main')->name('meetpat-reseller')->middleware('auth')->middleware('reseller');
// TODO Reseller routes and controllers 

/** END Reseller Routes */

/** BEGIN MeetPAT Client Routes */

Route::get('/meetpat-client', 'MeetpatClientController@main')->name('meetpat-client')->middleware('auth');

// Dashboard Home Links
Route::get('/meetpat-client/data-visualisation', 'DataVisualisationController@index')->name('meetpat-data-visualisation')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/tutorials', 'MeetpatClientController@tutorials')->name('meetpat-tutorials')->middleware('auth')->middleware('client');

// Account Sync Pages
Route::get('/meetpat-client/sync-platform', 'MeetpatClientController@sync_platform')->name('meetpat-client-sync')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/upload-clients', 'MeetpatClientController@upload_clients')->name('meetpat-client-upload')->middleware('auth')->middleware('client');

// Google Register Routes
Route::get('/register-google-ad-account', 'GoogleCustomerAudienceController@register_ad_account_id')->name('google-ad-account')->middleware('auth')->middleware('client');


// Route::post('/google-authorization/authenticate-authorization-code', 'MeetpatClientController@authenticate_authorization_code')->name('authenticate-google-code')->middleware('client');
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

Route::get('/meetpat-client/upload', 'MeetpatClientController@upload_main')->name('upload-main')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/upload-client-file-data', 'DataVisualisationController@large_data_upload_form')->name('upload-client-data')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/upload/update-custom-metrics', 'MeetpatClientController@update_custom_metrics')->name('update-custom-metrics')->middleware('auth')->middleware('client');

// Routes for user filtered customer audience

Route::post('/meetpat-client/create-selected-contacts', 'MeetpatClientController@create_filtered_audience')->name('sync-selected-contacts')->middleware('auth')->middleware('client');
Route::post('/meetpat-client/upload-audience-form', 'MeetpatClientController@submit_filtered_audience')->name('submit-filtered-audience')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/filtered-audience-form/{user_id}/{filtered_list_id}', 'MeetpatClientController@filtered_audience_form')->name('filtered-audience-form')->middleware('auth')->middleware('client');

// Account Settings

Route::get('/meetpat-client/settings', 'MeetpatClientController@account_settings')->name('account-settings')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/settings/notifications', 'MeetpatClientController@notification_settings')->name('notification-settings')->middleware('auth')->middleware('client');

// Client Files
Route::get('/meetpat-client/files', 'MeetpatClientController@files_main')->name('client-files')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/files/uploaded-audience-files', 'MeetpatClientController@files_uploaded')->name('client-uploaded-files')->middleware('auth')->middleware('client');
Route::get('/meetpat-client/files/saved-audience-files', 'MeetpatClientController@files_saved')->name('client-saved-files')->middleware('auth')->middleware('client');
/** END MeetPAT Client Routes */

Route::get('/test-bsapi', 'MiscController@bsapi')->name('bsapi');
Route::get('/test-bsapi-balances', 'MiscController@bsapi_balance')->name('bsapi-balances');

/* Temp Route */

Route::get('/test/s3-upload', 'MiscController@test_s3_upload')->name('test-s3-upload')->middleware('auth')->middleware('client');

//Route::get('/test-facebook-custom-audience', 'FacebookCustomerAudienceController@create_custom_audience')->name('test-upload');
