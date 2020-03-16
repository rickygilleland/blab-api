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
    return view('welcome');
});


Route::get('login/slack/callback', 'Auth\LoginController@handleSlackCallback');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

//onboarding routes
Route::get('onboarding/organization', 'OnboardingController@organization');
Route::post('onboarding/organization', 'OnboardingController@organization_update');
Route::get('onboarding/team', 'OnboardingController@team');
Route::post('onboarding/team', 'OnboardingController@team_update');
Route::get('onboarding/room', 'OnboardingController@room');
Route::post('onboarding/room', 'OnboardingController@room_update');

Route::get('/o/{organization_slug}/{team_slug}', 'TeamController@show', function($organization_slug, $team_slug) {

})->middleware('auth');

//rooms routes

Route::get('/o/{organization_slug}/{team_slug}/{room_slug}', 'RoomController@show', function($organization_slug, $team_slug, $room_slug) {

})->middleware('auth');