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
Route::post('meetpat-client/large-data/upload', 'DataVisualisationController@handle_upload')->name('large-data-upload-handler');
Route::post('meetpat-client/large-data/delete', 'DataVisualisationController@handle_delete_upload')->name('delete-data-upload-handler');


Route::post('meetpat-client/get-records', 'DataVisualisationController@get_records')->name('get-client-records');
// Separate calls
Route::post('meetpat-client/get-records/municipalities', 'DataVisualisationController@get_municipalities')->name('get-client-records-municipalities');

// Get Records Job Que Update

Route::post('meetpat-client/get-job-que', 'DataVisualisationController@get_job_que')->name('get-client-jobs-in-que');