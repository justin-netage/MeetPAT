<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Email Routes

Route::post('/send-message', 'ContactController@send_message')->name('send-message');

// End Administrator routes

// Administrator routes
// Note add ->middleware('auth')

Route::get('/meetpat-admin/users', 'AdministratorController@users')->name('users');
Route::get('/meetpat-admin/users/count', 'AdministratorController@user_count')->name('user-count');

Route::post('/meetpat-admin/users/create', 'AdministratorController@create_user')->name('create-user')->middleware('auth');
Route::post('/meetpat-admin/users/edit', 'AdministratorController@edit_user')->name('edit-user');
Route::post('/meetpat-admin/users/active-status-change', 'AdministratorController@active_change')->name('change-user-status');
Route::post('/meetpat-admin/users/delete', 'AdministratorController@delete')->name('delete-user');

Route::post('/meetpat-admin/users/unique-email', 'AdministratorController@unique_email')->name('unique-email');

// End Administrator routes

// Password Generator
Route::get('/meetpat-admin/generate-password', 'MiscController@generate_password')->name('password-generator');

// MeetPAT Client request handlers
Route::post('meetpat-client/upload-custom-audience', 'MeetpatClientController@upload_customers_handle')->name('upload-customers-request');
Route::post('meetpat-client/upload-custom-audience/facebook', 'MeetpatClientController@facebook_custom_audience_handler')->name('upload-facebook-request');
Route::post('meetpat-client/upload-custom-audience/google', 'MeetpatClientController@google_custom_audience_handler')->name('upload-google-request');

//Route::post('meetpat-client/request-facebook-api', 'FacebookCustomerAudienceController@facebook_upload_handler')->name('facebook-request-api-handler');

Route::post('meetpat-client/upload-google-custom-audience', 'GoogleCustomerAudienceController@upload_google_customers_handle')->name('google-upload-customers-request');
Route::post('meetpat-client/request-google-api', 'GoogleCustomerAudienceController@google_upload_handler')->name('google-request-api-handler');

Route::post('upload-file', 'MeetpatClientController@handle_upload')->name('uploader');
Route::post('delete-file', 'MeetpatClientController@handle_delete_upload')->name('delete-file');

// API route to handle large file of client data

Route::post('meetpat-client/large-data/handler', 'DataVisualisationController@large_data_upload')->name('large-data-handler');
Route::post('meetpat-client/large-data/upload', 'MeetpatClientController@handle_upload')->name('large-data-upload-handler');
Route::post('meetpat-client/large-data/delete', 'MeetpatClientController@handle_delete_upload')->name('delete-data-upload-handler');


Route::get('meetpat-client/get-records', 'DataVisualisationController@get_records')->name('get-client-records');
// Separate calls
Route::get('meetpat-client/get-records/count', 'DataVisualisationController@get_records_count')->name('get-client-records-count');
Route::get('meetpat-client/get-records/municipalities', 'DataVisualisationController@get_municipalities')->name('get-client-records-municipalities');
Route::get('meetpat-client/get-records/provinces', 'DataVisualisationController@get_provinces')->name('get-client-records-provinces');
Route::get('meetpat-client/get-records/ages', 'DataVisualisationController@get_ages')->name('get-client-records-ages');
Route::get('meetpat-client/get-records/genders', 'DataVisualisationController@get_genders')->name('get-client-records-genders');
Route::get('meetpat-client/get-records/population-groups', 'DataVisualisationController@get_population_groups')->name('get-client-records-population-groups');
Route::get('meetpat-client/get-records/home-owner', 'DataVisualisationController@get_home_owner')->name('get-client-records-home-owner');
Route::get('meetpat-client/get-records/household-income', 'DataVisualisationController@get_household_income')->name('get-client-records-household-income');
Route::get('meetpat-client/get-records/risk-category', 'DataVisualisationController@get_risk_category')->name('get-client-records-risk-category');
Route::get('meetpat-client/get-records/director-of-business', 'DataVisualisationController@get_director_of_business')->name('get-client-records-director-of-business');
Route::get('meetpat-client/get-records/citizens-and-residents', 'DataVisualisationController@get_citizens_and_residents')->name('get-client-records-citizens-and-residents');
Route::get('meetpat-client/get-records/generations', 'DataVisualisationController@get_generations')->name('get-client-records-generations');
Route::get('meetpat-client/get-records/marital-statuses', 'DataVisualisationController@get_marital_statuses')->name('get-client-records-marital-statuses');
Route::get('meetpat-client/get-records/areas', 'DataVisualisationController@get_area')->name('get-client-records-areas');


// Get Authentication token
Route::post('google-authorization/authenticate-authorization-code', 'MeetpatClientController@authenticate_authorization_code')->name('authenticate-google-code');


// Get Records Job Que Update

Route::post('meetpat-client/get-job-que', 'DataVisualisationController@get_job_que')->name('get-client-jobs-in-que');

// Add Filtered list to "Job Que"
Route::post('meetpat-client/submit-audience/add-to-que', 'MeetpatClientController@add_filtered_list_to_que')->name('add-filtered-list-to-que');
// Google
// run Job
Route::post('/meetpat-client/submit-audience/run-job-google', 'MeetpatClientController@run_google_job_que')->name('run-google-job-que');
// Facebook
// run Job
Route::post('/meetpat-client/submit-audience/run-job-facebook', 'MeetpatClientController@run_facebook_job_que')->name('run-facebook-job-que');

//save settings
Route::post('/meetpat-client/settings/save-changes', 'MeetpatClientController@save_settings')->name('save-settings');

// Disconnect plarform
Route::post('/meetpat-client/settings/disconnect-platform', 'MeetpatClientController@disconnect_platform')->name('disconnect-platform');