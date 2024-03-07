<?php

use App\Http\Controllers\DiginetHR\ApiController\TransferAllDiginetDataForApi;
use App\Http\Controllers\DiginetHR\ApiController\TransferDiginetDataBusinessTripsForApi;
use App\Http\Controllers\DiginetHR\ApiController\TransferDiginetDataEmployeeHoursForApi;
use App\Http\Controllers\DiginetHR\ApiController\TransferDiginetDataEmployeeLeavesForApi;
use App\Http\Controllers\DiginetHR\ApiController\TransferDiginetDataEmployeeOvertimesForApi;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/transfer-data-diginet',
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::post('employee-hours', [TransferDiginetDataEmployeeHoursForApi::class, 'store']);
    Route::post('employee-leave', [TransferDiginetDataEmployeeLeavesForApi::class, 'store']);
    Route::post('employee-overtime', [TransferDiginetDataEmployeeOvertimesForApi::class, 'store']);
    Route::post('business-trip', [TransferDiginetDataBusinessTripsForApi::class, 'store']);
    Route::post('update-all-tables', [TransferAllDiginetDataForApi::class, 'update'])->name('update-all-tables.update');
    Route::post('delete-all-tables', [TransferAllDiginetDataForApi::class, 'delete'])->name('delete-all-tables.delete');
});
