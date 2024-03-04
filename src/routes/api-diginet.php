<?php

use App\Http\Controllers\DiginetHR\TransferDiginetDataBusinessTripsForApi;
use App\Http\Controllers\DiginetHR\TransferDiginetDataEmployeeHoursForApi;
use App\Http\Controllers\DiginetHR\TransferDiginetDataEmployeeLeavesForApi;
use App\Http\Controllers\DiginetHR\TransferDiginetDataEmployeeOvertimesForApi;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/transfer-data-diginet',
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::post('employee-hours', [TransferDiginetDataEmployeeHoursForApi::class, 'store']);
    Route::post('employee-leave', [TransferDiginetDataEmployeeLeavesForApi::class, 'store']);
    Route::post('employee-overtime', [TransferDiginetDataEmployeeOvertimesForApi::class, 'store']);
    Route::post('business-trip', [TransferDiginetDataBusinessTripsForApi::class, 'store']);
});
