<?php

use App\Http\Controllers\Reports\ReportIndexController;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Route;

Route::get('reports', [ReportIndexController::class, 'index'])->name('reportIndices.index');

Route::group([
    'middleware' => ['auth', 'impersonate',]
], function () {
    Route::group([
        'prefix' => 'reports'
    ], function () {
        $routes = Report::getAllRoutes();
        Route::group([
            //Production cant open WIR Register, therefore cant create WIR Register
            // 'middleware' => "role:READ-WRITE-DATA-" . Str::upper($entityName) . "|ADMIN-DATA-" . Str::upper($entityName),
        ], function () use ($routes) {
            foreach ($routes as $route) {
                ['path' => $path, 'name' => $name, 'routeName' => $routeName,] = $route;
                Route::get($name, [$path, 'index'])->name($routeName);
                Route::get("{$routeName}_ep", [$path, "exportCSV"])->name("{$routeName}_ep.exportCSV");
            }
        });
    });
});
