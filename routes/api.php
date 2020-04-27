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

Route::middleware('auth:api')->get('/user', 'UserController@show');

Route::middleware('auth:api')->get('/organization', 'OrganizationController@api_show');

Route::middleware('auth:api')->get('/organization/users', 'OrganizationController@get_organization_users');

Route::middleware('auth:api')->get('/organization/teams', 'OrganizationController@get_organization_teams');
