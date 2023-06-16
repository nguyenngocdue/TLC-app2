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
                ['path' => $path, 'name' => $name,] = $route;
                Route::get($name, [$path, 'index'])->name($name);
                Route::get("{$name}_ep", [$path, "exportCSV"])->name("{$name}_ep.exportCSV");
            }
        });
    });
});
