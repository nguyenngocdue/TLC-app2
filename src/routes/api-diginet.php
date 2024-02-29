<?php

use App\Http\Controllers\DiginetHR\TransferDiginetBusinessTripLinesForApi;
use App\Http\Controllers\DiginetHR\TransferDiginetDataEmployeeHoursForApi;
use App\Http\Controllers\DiginetHR\TransferDiginetDataEmployeeLeaveLinesForApi;
use App\Http\Controllers\DiginetHR\TransferDiginetDataOvertimeLinesForApi;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/transfer-data-diginet',
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::post('employee-hours', [TransferDiginetDataEmployeeHoursForApi::class, 'store']);
    Route::post('employee-leave', [TransferDiginetDataEmployeeLeaveLinesForApi::class, 'store']);
    Route::post('employee-overtime', [TransferDiginetDataOvertimeLinesForApi::class, 'store']);
    Route::post('business-trip', [TransferDiginetBusinessTripLinesForApi::class, 'store']);
});
