<?php

use App\Http\Controllers\DiginetHR\DiginetBusinessTripLinesController;
use App\Http\Controllers\DiginetHR\DiginetDataController;
use App\Http\Controllers\DiginetHR\DiginetEmployeeHoursController;
use App\Http\Controllers\DiginetHR\DiginetEmployeeLeaveLinesController;
use App\Http\Controllers\DiginetHR\DiginetEmployeeLeaveSheetsController;
use App\Http\Controllers\DiginetHR\DiginetEmployeeOvertimeLinesController;
use App\Http\Controllers\DiginetHR\DiginetEmployeeOvertimeSheetController;
use App\Http\Controllers\DiginetHR\DiginetUpdateAllTablesController;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => 'diginet/transfer-data-diginet',
        'middleware' => ['auth:sanctum', 'throttle:600,1'],
    ],
    function () {
        Route::get('/', [DiginetDataController::class, 'index'])->name('diginet.index');
        Route::get('employee-hours', [DiginetEmployeeHoursController::class, 'index'])->name('employee-hours.index');
        Route::get('employee-leave-sheets', [DiginetEmployeeLeaveSheetsController::class, 'index'])->name('employee-leave-sheets.index');
        Route::get('employee-leave-lines', [DiginetEmployeeLeaveLinesController::class, 'index'])->name('employee-leave-lines.index');
        Route::get('employee-overtime-sheets', [DiginetEmployeeOvertimeSheetController::class, 'index'])->name('employee-overtime-sheets.index');
        Route::get('employee-overtime-lines', [DiginetEmployeeOvertimeLinesController::class, 'index'])->name('employee-overtime-lines.index');
        Route::get('business-trip-sheets', [DiginetBusinessTripLinesController::class, 'index'])->name('business-trip-sheets.index');
        Route::get('business-trip-lines', [DiginetBusinessTripLinesController::class, 'index'])->name('business-trip-lines.index');
        Route::get('all-tables', [DiginetUpdateAllTablesController::class, 'index'])->name('all-tables.index');
    }
);
