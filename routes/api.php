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


/** BEGIN Administrator routes */
// Note add ->middleware('auth')

Route::get('/meetpat-admin/get-users', 'AdministratorController@get_users')->name('get-users')->middleware('auth:api');

Route::get('/meetpat-admin/users', 'AdministratorController@users')->name('users');
Route::get('/meetpat-admin/users/count', 'AdministratorController@user_count')->name('user-count');

Route::post('/meetpat-admin/users/create', 'AdministratorController@create_user')->name('create-user')->middleware('auth');
Route::post('/meetpat-admin/users/edit', 'AdministratorController@edit_user')->name('edit-user');
Route::post('/meetpat-admin/users/active-status-change', 'AdministratorController@active_change')->name('change-user-status')->middleware('auth:api');
Route::post('/meetpat-admin/users/delete', 'AdministratorController@delete_user')->name('delete-user');

Route::post('/meetpat-admin/users/unique-email', 'AdministratorController@unique_email')->name('unique-email');

Route::get('/meetpat-admin/clients/all', 'AdministratorController@all_clients')->name('all-clients')->middleware('auth:api');
Route::get('/meetpat-admin/client/get', 'AdministratorController@get_client')->name('get-client')->middleware('auth:api');
Route::post('/meetpat-admin/clients/files', 'AdministratorController@get_user_files')->name('get-user-files')->middleware('auth:api');
Route::get('/meetpat-admin/clients/get-magic-link', 'AdministratorController@get_magic_link')->name('get-magic-link')->middleware('auth:api');

Route::get('/meetpat-admin/resellers/all', 'AdministratorController@all_resellers')->name('all-resellers')->middleware('auth:api');

// User settings
Route::post('/meetpat-admin/settings/clear-uploads', 'AdministratorController@clear_user_uploads')->name('clear-uploads');
Route::post('/meetpat-admin/settings/remove-affiliate', 'AdministratorController@remove_affiliate')->name('remove-affiliate');
Route::post('/meetpat-admin/settings/updated-upload-limit', 'AdministratorController@set_upload_limit')->name('change-upload-limit')->middleware('auth:api');
Route::post('/meetpat-admin/settings/updated-credit-limit', 'AdministratorController@set_similar_audience_limit')->name('change-credit-limit');

Route::post('/meetpat-admin/delete-file', 'AdministratorController@delete_file')->name('delete-file')->middleware('auth:api');

// Enriched Data Tracking
Route::get('/meetpat-admin/enriched-data-tracked-day', 'AdministratorController@get_enriched_data_tracking_day')->name('get-enriched-data-tracking-day');
Route::get('/meetpat-admin/enriched-data-tracked-monthly', 'AdministratorController@get_enriched_data_tracking_monthly')->name('get-enriched-data-tracking-monthly');

// Running Jobs
// TODO: route to get jobs ( default is running jobs ) GET
Route::get('/meetpat-admin/running-jobs', 'AdministratorController@get_running_jobs')->name('get-running-jobs')->middleware('auth:api');
Route::post('/meetpat-admin/cancel-job', 'AdministratorController@cancel_job')->name('cancel-running-job')->middleware('auth:api');
// TODO: route to action job ( Cancel ) if file has not been sent to BSA yet POST
// TODO: route to action job ( Restart ) if completed or canceled POST
// TODO: route to get job results ( current and complete ) GET



/** END Administrator routes */

// Password Generator
Route::get('/meetpat-admin/generate-password', 'MiscController@generate_password')->name('password-generator');

// TODO -> API routes for admin create reseller.

/** BEGIN Client routes */

Route::post('meetpat-client/upload-custom-audience', 'MeetpatClientController@upload_customers_handle')->name('upload-customers-request');
Route::post('meetpat-client/upload-custom-audience/facebook', 'MeetpatClientController@facebook_custom_audience_handler')->name('upload-facebook-request');
Route::post('meetpat-client/upload-custom-audience/google', 'MeetpatClientController@google_custom_audience_handler')->name('upload-google-request');

// Update custom metric data

Route::post('meetpat-client/update/custom-metrics/handler', 'MeetpatClientController@custom_metrics_handler')->name('custom-metrics-handler');
Route::post('meetpat-client/update/custom-metrics/handle-file-upload', 'MeetpatClientController@handle_file_upload')->name('handle-update-file-upload');
Route::post('meetpat-client/update/custom-metrics/handle-remove-upload', 'MeetpatClientController@handle_remove_upload')->name('handle-remove-upload');

Route::get('meetpat-client/update/get-job-queue', 'MeetpatClientController@get_job_queue')->name('get-job-queue-status');

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

// Grouped calls
Route::get('meetpat-client/get-location-data', 'DataVisualisationController@get_location_data')->name('get-location-records')->middleware('auth:api');
Route::get('meetpat-client/get-demographic-data', 'DataVisualisationController@get_demographic_data')->name('get-demographic-records')->middleware('auth:api');
Route::get('meetpat-client/get-assets-data', 'DataVisualisationController@get_assets_data')->name('get-assets-records')->middleware('auth:api');
Route::get('meetpat-client/get-financial-data', 'DataVisualisationController@get_financial_data')->name('get-financial-records')->middleware('auth:api');
Route::get('meetpat-client/get-custom-metrics-data', 'DataVisualisationController@get_custom_variable_data')->name('get-custom-metrics-records')->middleware('auth:api');


// Separate calls
Route::get('meetpat-client/get-records/count', 'DataVisualisationController@get_records_count')->name('get-client-records-count');//->middleware('auth:api');
Route::get('meetpat-client/get-records/municipalities', 'DataVisualisationController@get_municipalities')->name('get-client-records-municipalities')->middleware('auth:api');
Route::get('meetpat-client/get-records/provinces', 'DataVisualisationController@get_provinces')->name('get-client-records-provinces')->middleware('auth:api');
Route::get('meetpat-client/get-records/ages', 'DataVisualisationController@get_ages')->name('get-client-records-ages')->middleware('auth:api');
Route::get('meetpat-client/get-records/genders', 'DataVisualisationController@get_genders')->name('get-client-records-genders')->middleware('auth:api');
Route::get('meetpat-client/get-records/population-groups', 'DataVisualisationController@get_population_groups')->name('get-client-records-population-groups')->middleware('auth:api');
Route::get('meetpat-client/get-records/home-owner', 'DataVisualisationController@get_home_owner')->name('get-client-records-home-owner')->middleware('auth:api');
Route::get('meetpat-client/get-records/household-income', 'DataVisualisationController@get_household_income')->name('get-client-records-household-income')->middleware('auth:api');
Route::get('meetpat-client/get-records/risk-category', 'DataVisualisationController@get_risk_category')->name('get-client-records-risk-category')->middleware('auth:api');
Route::get('meetpat-client/get-records/director-of-business', 'DataVisualisationController@get_director_of_business')->name('get-client-records-director-of-business')->middleware('auth:api');
Route::get('meetpat-client/get-records/citizens-and-residents', 'DataVisualisationController@get_citizens_and_residents')->name('get-client-records-citizens-and-residents')->middleware('auth:api');
Route::get('meetpat-client/get-records/generations', 'DataVisualisationController@get_generations')->name('get-client-records-generations')->middleware('auth:api');
Route::get('meetpat-client/get-records/marital-statuses', 'DataVisualisationController@get_marital_statuses')->name('get-client-records-marital-statuses')->middleware('auth:api');
Route::get('meetpat-client/get-records/areas', 'DataVisualisationController@get_area')->name('get-client-records-areas')->middleware('auth:api')->middleware('auth:api');
Route::get('meetpat-client/get-records/vehicle-owner', 'DataVisualisationController@get_vechicle_owner')->name('get-vehicle-owners')->middleware('auth:api');
Route::get('meetpat-client/get-records/lsm-group', 'DataVisualisationController@get_lsm_group')->name('get-lsm-groups')->middleware('auth:api');
Route::get('meetpat-client/get-records/property-valuation', 'DataVisualisationController@get_property_valuation')->name('get-property-valuations')->middleware('auth:api');
Route::get('meetpat-client/get-records/property-count-bucket', 'DataVisualisationController@get_property_count_bucket')->name('get-property-count-buckets')->middleware('auth:api');
Route::get('meetpat-client/get-records/primary-property-type', 'DataVisualisationController@get_primary_property_type')->name('get-primary-property-type')->middleware('auth:api');
Route::get('meetpat-client/get-records/employers', 'DataVisualisationController@get_employer')->name('get-employers')->middleware('auth:api');

Route::get('meetpat-client/get-saved-audiences', 'DataVisualisationController@get_saved_audiences')->name('get-audience-files')->middleware('auth:api');
Route::post('meetpat-client/filtered-audience/save', 'DataVisualisationController@save_filtered_audience')->name('save-filtered-audience');
Route::post('meetpat-client/delete-saved-audience-file', 'DataVisualisationController@delete_filtered_audience_file')->name('delete-filtered-audience-file');
Route::post('meetpat-client/save-filename-edits', 'DataVisualisationController@save_file_names')->name('save-filtered-audience-file-names');
// Check if queued job is complete
Route::post('meetpat-client/saved-file-job-status', 'DataVisualisationController@check_job_complete')->name('check-save-file-status');
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
Route::post('/meetpat-client/settings/update', 'MeetpatClientController@update_notification_settings')->name('update-notification-settings')->middleware('auth:api');

// Audience files 
Route::get('/meetpat-client/files/get-uploaded-audiences', 'MeetpatClientController@get_uploaded_files')->name('get-uploaded-audiences')->middleware('auth:api');
Route::get('/meetpat-client/files/get-saved-audiences', 'MeetpatClientController@get_saved_files')->name('get-saved-audiences')->middleware('auth:api');

// Disconnect plarform
Route::post('/meetpat-client/settings/disconnect-platform', 'MeetpatClientController@disconnect_platform')->name('disconnect-platform');

// Filter Job Queue
Route::get('/meetpat-client/filter-job-status', 'DataVisualisationController@check_job_status')->name('check-filter-job-status')->middleware('auth:api');
Route::post('/meetpat-client/submit-filter', 'DataVisualisationController@queue_filter_job')->name('queue-filter-job')->middleware('auth:api');

// Platform Authorizations
Route::post('/meetpat-client/sync/facebook/deauthorize', 'FacebookCustomerAudienceController@deauthorize')->name('deauthorize-facebook')->middleware('auth:api');

// Create Custom Audience/List
Route::post('/meetpat-client/facebook/custom-audience/create', 'FacebookCustomerAudienceController@create_custom_audience')->name('create-custom-audience-facebook')->middleware('auth:api');
Route::post('/meetpat-client/google/custom-audience/create', 'GoogleCustomAudienceController@create_custom_audience')->name('create-custom-audience-google')->middleware('auth:api');
/** END Client routes */

// Upload audience file to MeetPAT
Route::get('/meetpat-client/large-data/uploads-available', 'MeetpatClientController@get_user_uploads')->name('get-user-uploads')->middleware('auth:api');
Route::post('/meetpat-client/large-data/check-file', 'MeetpatClientController@add_to_file_check_queue')->name('add-to-file-check-queue')->middleware('auth:api');
Route::get('/meetpat-client/check-file-job', 'MeetpatClientController@check_file_job')->name('check-file-job')->middleware('auth:api');

// Barker Street Access

Route::post('/file-ready', 'ApiController@file_ready')->middleware('auth:api')->name('file-updated');

// S3 Credentials

Route::get('/get-aws-credentials', 'ApiController@get_aws_credentials')->middleware('auth:api')->name('get-aws-credentials');