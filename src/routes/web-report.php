<?php

use App\Http\Controllers\Reports\ReportIndexController;
use App\Utils\Support\Entities;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('reports', [ReportIndexController::class, 'index'])->name('reportIndices.index');

Route::group([
    'middleware' => ['auth', 'impersonate',]
], function () {
    $entities = Entities::getAll();
    Route::group([
        'prefix' => 'reports'
    ], function () use ($entities) {
        foreach ($entities as $entity) {
            $entityName = Str::getEntityName($entity);
            $singular = Str::singular($entityName);
            $ucfirstName = Str::ucfirst($singular);
            Route::group([
                //Production cant open WIR Register, therefore cant create WIR Register
                // 'middleware' => "role:READ-WRITE-DATA-" . Str::upper($entityName) . "|ADMIN-DATA-" . Str::upper($entityName),
            ], function () use ($singular, $ucfirstName) {
                for ($i = 10; $i <= 100; $i += 10) {
                    $mode = str_pad($i, 3, '0', STR_PAD_LEFT);
                    // dd($mode);
                    $path = "App\\Http\\Controllers\\Reports\\Reports\\{$ucfirstName}_$mode";
                    $routeName = 'report-' . $singular . "_" . $mode;
                    $name = 'report-' . $singular . "/$mode";
                    if (class_exists($path)) {
                        Route::get($name, [$path, 'index'])->name($routeName);
                        Route::get("{$routeName}_ep", [$path, "exportCSV"])->name("{$routeName}_ep.exportCSV");
                    }

                    $path = "App\\Http\\Controllers\\Reports\\Registers\\{$ucfirstName}_$mode";
                    $routeName = 'register-' . $singular . "_" . $mode;
                    $name = 'register-' . $singular . "/$mode";
                    if (class_exists($path)) {
                        Route::get($name, [$path, 'index'])->name($routeName);
                        Route::get("{$name}_ep", [$path, 'exportCSV'])->name("{$routeName}_ep.exportCSV");
                        // Route::get("{$routeName}_ep", [$path, "exportCSV"])->name("{$routeName}_ep.exportCSV");
                    }

                    $path = "App\\Http\\Controllers\\Reports\\Documents\\{$ucfirstName}_$mode";
                    $routeName = 'document-' . $singular . "_" . $mode;
                    $name = 'document-' . $singular . "/$mode";
                    if (class_exists($path)) {
                        Route::get($name, [$path, 'index'])->name($routeName);
                        Route::get("{$routeName}_ep", [$path, "exportCSV"])->name("{$routeName}_ep.exportCSV");
                    }
                }
            });
        }
    });
});
