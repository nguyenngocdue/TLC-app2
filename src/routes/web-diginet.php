<?php

use App\Http\Controllers\DiginetHR\DiginetEmployeeHourController;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => 'diginet/transfer-data-diginet',
        'middleware' => ['auth:sanctum', 'throttle:600,1'],
    ],
    function () {
        Route::get('employee_hours', [DiginetEmployeeHourController::class, 'index'])->name('employee_hours.index');
    }
);
