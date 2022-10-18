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

Route::group([
    'prefix' => 'v1/auth',
], function () {
    Route::post('login', [App\Http\Controllers\Api\v1\Auth\AuthController::class, 'login']);
    Route::post('signup', [App\Http\Controllers\Api\v1\Auth\AuthController::class, 'register']);
    Route::post('password/email', [App\Http\Controllers\Api\v1\Auth\ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::get('password/reset/{token}', [App\Http\Controllers\Api\v1\Auth\ResetPasswordController::class, 'tokenResetPassword']);
    Route::post('password/reset', [App\Http\Controllers\Api\v1\Auth\ResetPasswordController::class, 'reset']);
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('logout', [App\Http\Controllers\Api\v1\Auth\AuthController::class, 'logout']);
        Route::get('user', [App\Http\Controllers\Api\v1\Auth\AuthController::class, 'user']);
    });
});
