<?php

use App\Utils\System\Memory;
use App\Utils\System\Timer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

Timer::startTimeCounter();
Memory::startMemory();
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
Route::group([
    'prefix' => 'v1/prod',
], function () {
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('sub_projects', [App\Http\Controllers\Api\v1\Production\ProductionController::class, 'getSubProjects']);
        Route::get('prod_run_line/data', [App\Http\Controllers\Api\v1\Production\ProductionController::class, 'getDataProductionLine']);
        Route::get('sub_projects/{id}', [App\Http\Controllers\Api\v1\Production\ProductionController::class, 'getProdOrders']);
        Route::get('sub_projects/{id1}/prod_orders/{id2}', [App\Http\Controllers\Api\v1\Production\ProductionController::class, 'getProdOrders']);
        Route::resource("prod_run", App\Http\Controllers\Api\v1\Production\ProductionRunController::class);
        Route::put("prod_run/stopped/{prod_run_id}", [App\Http\Controllers\Api\v1\Production\ProductionRunController::class, 'stopped'])->name('prod_run.stopped');
        Route::resource("prod_run_line", App\Http\Controllers\Api\v1\Production\ProductionRunLineController::class);
        Route::get("prod_run_line/duplicate/{id}", [App\Http\Controllers\Api\v1\Production\ProductionRunLineController::class, 'duplicate'])->name('prod_run_line.duplicate');
        Route::get("prod_run_line/{sub_project_id}/{prod_order_id}/{prod_routing_link_id}", [App\Http\Controllers\Api\v1\Production\ProductionRunLineController::class, 'prodLine'])->name('prod_run.live');
    });
});
Route::group([
    'prefix' => 'v1/qaqc',
], function () {
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::post('upload_file', [App\Http\Controllers\Api\v1\UploadFile::class, 'upload']);
    });
});
Route::group([
    'prefix' => 'v1/login',
], function () {
    Route::get('google', [App\Http\Controllers\Api\v1\Auth\SocialiteAuthController::class, 'redirectToGoogle']);
    Route::get('google/callback', [App\Http\Controllers\Api\v1\Auth\SocialiteAuthController::class, 'handleGoogleCallback']);
});
