<?php

use App\Http\Controllers\Api\MonitorController;
use App\Http\Controllers\Api\UrlController;
use App\Http\Controllers\Api\UrlEventStreamController;
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
    Route::apiResource('urls', UrlController::class);

    Route::apiResource('bulk-monitor', MonitorController::class)->only([
        'store',
        'show'
    ])->parameters(['bulk-monitor' => 'url']);

});

Route::get('user/{user}/requests/watch', [UrlEventStreamController::class, 'streamLastRequest']);
