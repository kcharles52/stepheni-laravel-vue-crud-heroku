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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
