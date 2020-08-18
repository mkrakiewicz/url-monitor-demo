<?php

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

Route::group(['middleware' => 'auth:sanctum', 'prefix' => '/user/{user}', 'as' => 'user.'], function () {
    Route::apiResource('urls', 'UrlController');

    Route::post('bulk-monitor', 'MonitorController@store');
//    Route::get('bulk-monitors/{url:url}', 'MonitorController@index')->where('url', '.*');
    Route::get('bulk-monitor/{url}', 'MonitorController@index');
});
