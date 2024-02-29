<?php

use App\Http\Controllers\TransferDiginetDataForApi;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/transfer-data-diginet',
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::post('employee-hours', [TransferDiginetDataForApi::class, 'store']);
});
