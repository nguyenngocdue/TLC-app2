<?php

use App\Http\Controllers\Entities\ManageJsonController;
use App\Http\Controllers\Workflow\ManageAppCreationsController;
use App\Http\Controllers\Workflow\ManageAppsController;
use App\Http\Controllers\Workflow\ManageStatusesController;
use App\Http\Controllers\Workflow\ManageWidgetsController;
use App\Utils\Support\Entities;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::group([
    'prefix' => 'dashboard/workflow',
    'middleware' => ['auth'],
], function () {
    Route::resource('manageStatuses', ManageStatusesController::class)->only('index', 'store', 'create');
    Route::resource('manageWidgets', ManageWidgetsController::class)->only('index', 'store', 'create');
    Route::resource('manageApps', ManageAppsController::class)->only('index', 'store', 'create');
    Route::resource('manageAppCreations', ManageAppCreationsController::class)->only('index', 'store');
});

Route::group([
    'middleware' => ['auth', 'impersonate',]
], function () {
    $entities = Entities::getAll();

    Route::group([
        'prefix' => 'config'
    ], function () use ($entities) {
        foreach ($entities as $entity) {
            $entityName = Str::getEntityName($entity);
            $singular = Str::singular($entityName);
            Route::group([
                'middleware' => "role:ADMIN-DATA-" . Str::upper($entityName),
            ], function () use ($singular) {

                Route::resource("{$singular}_ppt", ManageJsonController::class)->only('index', 'store', 'create');

                Route::resource("{$singular}_prp", ManageJsonController::class)->only('index', 'store', 'create');
                Route::resource("{$singular}_dfv", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rls", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_ltn", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_stt", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_tst", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_wdw", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_atb", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_dfn", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_itm", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_bic", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rtm", ManageJsonController::class)->only('index', 'store');

                Route::resource("{$singular}_vsb", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rol", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rqr", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_hdn", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_vsb-wl", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rol-wl", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rqr-wl", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_hdn-wl", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_cpb", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_unt", ManageJsonController::class)->only('index', 'store', 'create');
            });
        }
    });
});
