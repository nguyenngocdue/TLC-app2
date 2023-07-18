<?php

use App\Http\Controllers\Reports\ReportIndexController;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['auth', 'impersonate',]
], function () {
    Route::group([
        'prefix' => 'pivot-reports'
    ], function () {
        $routes = PivotReport::getAllRoutes();
        Route::group([
        ], function () use ($routes) {
            foreach ($routes as $route) {
                ['path' => $path, 'name' => $name,] = $route;
                Route::get($name, [$path, 'index'])->name($name);
                Route::get("{$name}_ep", [$path, "exportCSV"])->name("{$name}_ep.exportCSV");
            }
        });
    });
});
