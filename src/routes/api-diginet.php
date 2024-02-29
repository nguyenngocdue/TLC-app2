<?php

use App\Http\Controllers\DiginetHR\TransferDiginetDataEmployeeHoursForApi;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/transfer-data-diginet',
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::post('employee-hours', [TransferDiginetDataEmployeeHoursForApi::class, 'store']);
});
