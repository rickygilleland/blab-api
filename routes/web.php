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

Route::get('terms', function() {
    return view('terms');
});

Route::get('privacy', function() {
    return view('privacy');
});

Route::get('slack/oauth', 'SlackController@start');
Route::get('slack/callback', 'SlackController@callback');
Route::post('slack/hook', 'SlackController@handle_hook');

Route::get('login/token/{code}', 'Auth\LoginController@loginWithToken');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('invite/{code}', 'InviteController@index', function($code) {

});

Route::get('invite', 'InviteController@request_invite');
Route::post('invite', 'InviteController@submit_invite_request');

Route::get('get_started', function() {
    return view('invite.get_started');
});

Route::get('/admin/invite/create', 'InviteController@admin_create_invite_form');
Route::post('/admin/invite/create', 'InviteController@admin_create_invite');
Route::post('register', 'Auth\RegisterController@register')->name('register');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

//onboarding routes
Route::get('/onboarding/confirm', 'OnboardingController@confirm')->name('register_confirm');
Route::post('/onboarding/confirm', 'OnboardingController@register_confirm_code');
Route::get('onboarding/organization', 'OnboardingController@organization');
Route::post('onboarding/organization', 'OnboardingController@organization_update');
Route::get('onboarding/team', 'OnboardingController@team');
Route::post('onboarding/team', 'OnboardingController@team_update');
Route::get('onboarding/invite', 'OnboardingController@invite');
Route::post('onboarding/invite', 'OnboardingController@send_invite');
Route::get('onboarding/room', 'OnboardingController@room');
Route::post('onboarding/room', 'OnboardingController@room_update');
Route::get('onboarding/download', 'OnboardingController@download');

//billing routes
Route::get('/billing', 'BillingController@index');
Route::get('/billing/upgrade/{plan_name}', 'BillingController@show_upgrade_form', function($plan_name) {

});
Route::post('/billing/upgrade', 'BillingController@upgrade');
Route::get("/billing/portal", 'BillingController@redirectBillingPortal', function(Request $request) {

});
Route::get('/billing/success', function() {
    return view('billing.success');
});

//public blab viewing links
Route::get('/b/{organization_slug}/{blab_slug}', 'AttachmentController@show', function($organization_slug, $blab_slug) {

});

