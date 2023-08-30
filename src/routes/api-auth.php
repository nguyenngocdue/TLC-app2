<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\v1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\v1\Auth\SocialiteAuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/auth',
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'register']);
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'tokenResetPassword']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::get('verify', [AuthController::class, 'verify']);
    });
});

Route::group([
    'prefix' => 'v1/login',
], function () {
    Route::get('google', [SocialiteAuthController::class, 'redirectToGoogle'])->name('google_auth');
    Route::get('google/callback', [SocialiteAuthController::class, 'handleGoogleCallback']);
});
