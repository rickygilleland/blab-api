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
Route::middleware('auth:api')->patch('/user/{id}', 'UserController@update', function($id) {});

Route::middleware('auth:api')->get('/organization', 'OrganizationController@api_show');

Route::middleware('auth:api')->get('/organization/{id}/users', 'OrganizationController@get_organization_users', function ($id) {

});

Route::middleware('auth:api')->get('/organization/{id}/teams', 'OrganizationController@get_organization_teams', function ($id) {

});

Route::middleware('auth:api')->post('/organization/{id}/users/invite', 'OrganizationController@invite_users', function ($id) {

});

Route::middleware('auth:api')->post('/team', 'TeamController@create');

Route::middleware('auth:api')->post('/room', 'RoomController@create');
Route::middleware('auth:api')->get('/room/{id}/users', 'RoomController@get_users', function($id) { });
Route::middleware('auth:api')->post('/room/{id}/users', 'RoomController@invite_user', function($id) { });
Route::middleware('auth:api')->get('/room/{id}/messages', 'RoomController@get_messages', function($id) { });
Route::middleware('auth:api')->post('/room/{id}/messages', 'RoomController@create_message', function($id) { });
//Route::middleware('auth:api')->patch('/room/{id}/messages', 'RoomController@edit_message', function($id) { });

Route::middleware('auth:api')->post('/call', 'RoomController@create');
Route::middleware('auth:api')->get('/call/{id}/users', 'RoomController@get_users', function($id) { });
Route::middleware('auth:api')->post('/call/{id}/users', 'RoomController@invite_user', function($id) { });
Route::middleware('auth:api')->post('/call/{id}/answer', 'RoomController@answer_call', function($id) { });

Route::middleware('auth:api')->get('/messages/{id}', 'MessageController@get_message', function($id) { });
Route::middleware('auth:api')->post('/messages', 'MessageController@create_message', function() { });
Route::middleware('auth:api')->patch('/messages/{id}', 'MessageController@edit_message', function($id) { });

Route::middleware('auth:api')->get('/threads', 'ThreadController@get_user_threads', function() { });
Route::middleware('auth:api')->get('/threads/{id}', 'ThreadController@get_thread', function($id) { });
Route::middleware('auth:api')->get('/threads/{id}/messages', 'ThreadController@get_messages', function($id) { });

Route::middleware('auth:api')->get('/library/items', 'LibraryController@get_items', function() { });
Route::middleware('auth:api')->post('/library/items', 'LibraryController@create_item', function() { });