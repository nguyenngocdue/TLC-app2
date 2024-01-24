<?php

use App\BigThink\Options;
use App\Http\Controllers\Api\v1\System\NotificationsController;
use App\Http\Controllers\Api\v1\System\VersionController;
use Illuminate\Support\Facades\Route;
use Firebase\JWT\JWT;

Route::group([
    'prefix' => 'v1',
], function () {
    Route::get('options', [Options::class, 'getByKeys']);
    Route::post('options', [Options::class, 'setByKeyValues']);
});

Route::group([
    'prefix' => 'v1/system',
], function () {
    Route::get('app_version', [VersionController::class, 'version']);
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('notifications', [NotificationsController::class, 'notifications']);
        Route::get('notificationsRender', [NotificationsController::class, 'notificationsRender']);
        Route::get('getDiginetToken', fn () => ['token' => JWT::encode([], env('JWT_SECRET'), 'HS256')]);
    });
});
