<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/login/slack', 'Auth\LoginController@apiHandleSlackCallback');

Route::post('/login_code', 'Auth\LoginController@apiRequestLoginCode');

Route::post('/magic/auth', 'Auth\LoginController@apiMagicAuth');

Route::middleware('auth:api')->get('/user', 'UserController@show');

Route::middleware('auth:api')->get('/organization', 'OrganizationController@api_show');

Route::middleware('auth:api')->get('/organization/{id}/users', 'OrganizationController@get_organization_users', function ($id) {

});

Route::middleware('auth:api')->get('/organization/{id}/teams', 'OrganizationController@get_organization_teams', function ($id) {

});

Route::middleware('auth:api')->post('/organization/{id}/users/invite', 'OrganizationController@invite_users', function ($id) {

});

Route::middleware('auth:api')->post('/team', 'TeamController@create');

Route::middleware('auth:api')->post('/room', 'RoomController@create');