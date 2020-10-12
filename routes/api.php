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

// Authentication Routes...
Route::post('/v1/login', 'Auth\LoginController@login');
Route::get('/v1/logout', 'Auth\LoginController@logout');

// Registration Routes...
Route::post('/v1/register', 'Auth\RegisterController@create');

// Forum Routes....
Route::middleware('auth:api')->post('/v1/forum/create', 'ForumsController@create');
Route::middleware('auth:api')->post('/v1/forum/update/{forumId}', 'ForumsController@update');
Route::middleware('auth:api')->get('/v1/forum', 'ForumsController@fetchForums');
Route::middleware('auth:api')->get('/v1/forum/like/{forumId}', 'ForumsController@like');
Route::middleware('auth:api')->get('/v1/forum/comments/{forumId}', 'ForumsController@read');
Route::middleware('auth:api')->delete('/v1/forum/delete/{forumId}', 'ForumsController@delete');

// Comment Routes....
Route::middleware('auth:api')->post('/v1/comment/{forumId}', 'CommentsController@create');
Route::middleware('auth:api')->get('/v1/comments/{forumId}', 'CommentsController@fetchComments');

// Token Verification || Confirmation...
Route::middleware('auth:api')->get('/v1/user', function (Request $request) {
    return $request->user();
});
