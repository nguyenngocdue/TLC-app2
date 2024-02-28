<?php

use App\Http\Controllers\TransferDataDiginetToAppForApi;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/transfer-data-diginet',
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::post('employee-hours', [TransferDataDiginetToAppForApi::class, 'store']);
});
