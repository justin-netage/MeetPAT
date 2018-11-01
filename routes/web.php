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
Route::match(['get', 'post'], 'register', function(){
    return redirect(404);
});

Route::get('/home', 'HomeController@index')->name('home');
