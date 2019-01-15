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
Route::post('meetpat-client/upload-facebook-custom-audience', 'FacebookCustomerAudienceController@upload_facebook_customers_handle')->name('facebook-upload-customers-request');
