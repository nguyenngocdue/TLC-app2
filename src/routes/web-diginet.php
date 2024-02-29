<?php

use App\Http\Controllers\DiginetHR\DiginetEmployeeHoursController;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => 'diginet/transfer-data-diginet',
        'middleware' => ['auth:sanctum', 'throttle:600,1'],
    ],
    function () {
        Route::get('employee_hours', [DiginetEmployeeHoursController::class, 'index'])->name('employee_hours.index');
    }
);
