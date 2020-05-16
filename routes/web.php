<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome-coming-soon');
});

Route::get('login/token/{code}', 'Auth\LoginController@loginWithToken');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('invite/{code}', 'InviteController@index', function($code) {

});

Route::get('invite', 'InviteController@request_invite');
Route::post('invite', 'InviteController@submit_invite_request');

Route::get('/admin/invite/create', 'InviteController@admin_create_invite_form');
Route::post('/admin/invite/create', 'InviteController@admin_create_invite');
Route::post('register', 'Auth\RegisterController@register')->name('register');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

//onboarding routes
Route::get('onboarding/organization', 'OnboardingController@organization');
Route::post('onboarding/organization', 'OnboardingController@organization_update');
Route::get('onboarding/team', 'OnboardingController@team');
Route::post('onboarding/team', 'OnboardingController@team_update');
Route::get('onboarding/invite', 'OnboardingController@invite');
Route::post('onboarding/invite', 'OnboardingController@send_invite');
Route::get('onboarding/room', 'OnboardingController@room');
Route::post('onboarding/room', 'OnboardingController@room_update');
Route::get('onboarding/download', 'OnboardingController@download');

