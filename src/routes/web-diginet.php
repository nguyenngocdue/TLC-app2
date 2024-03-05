<?php

use App\Http\Controllers\DiginetHR\PageController\DiginetBusinessTripLinesController;
use App\Http\Controllers\DiginetHR\PageController\DiginetDataController;
use App\Http\Controllers\DiginetHR\PageController\DiginetEmployeeHoursController;
use App\Http\Controllers\DiginetHR\PageController\DiginetEmployeeLeaveLinesController;
use App\Http\Controllers\DiginetHR\PageController\DiginetEmployeeLeaveSheetsController;
use App\Http\Controllers\DiginetHR\PageController\DiginetEmployeeOvertimeLinesController;
use App\Http\Controllers\DiginetHR\PageController\DiginetEmployeeOvertimeSheetController;
use App\Http\Controllers\DiginetHR\PageController\DiginetShowDataEmployeeLeaveLinesController;
use App\Http\Controllers\DiginetHR\PageController\DiginetUpdateAllTablesController;
use App\Utils\Support\Diginet;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => 'diginet/transfer-diginet-data',
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

// Route::group(
//     [
//         'prefix' => 'diginet/show-diginet-data',
//         'middleware' => ['auth:sanctum', 'throttle:600,1'],
//     ],
//     function () {
//         Route::get('employee-leave-lines', [DiginetShowDataEmployeeLeaveLinesController::class, 'index'])->name('show-employee-leave-lines.index');
//     }
// );


Route::group([
    'middleware' => ['auth', 'impersonate',]
], function () {
    Route::group([
        'prefix' => 'diginet'
    ], function () {
        $routes = Diginet::getAllRoutes();
        // dd($routes);
        Route::group([], function () use ($routes) {
            foreach ($routes as $route) {
                ['path' => $path, 'name' => $name,] = $route;
                Route::get($name, [$path, 'index'])->name($name);
                Route::get("{$name}_ep", [$path, "exportCSV"])->name("{$name}_ep.exportCSV");
            }
        });
    });
});
