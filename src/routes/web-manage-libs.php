<?php

use App\Http\Controllers\Workflow\ManageAppCreationsController;
use App\Http\Controllers\Workflow\ManageAppsController;
use App\Http\Controllers\Workflow\ManageStatusesController;
use App\Http\Controllers\Workflow\ManageWidgetsController;
use App\Http\Controllers\Workflow\ManageStandardPropsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'dashboard/workflow',
    'middleware' => ['auth'],
], function () {
    Route::resource('manageStatuses', ManageStatusesController::class)->only('index', 'store', 'create');
    Route::resource('manageWidgets', ManageWidgetsController::class)->only('index', 'store', 'create');
    Route::resource('manageApps', ManageAppsController::class)->only('index', 'store', 'create');
    Route::resource('manageAppCreations', ManageAppCreationsController::class)->only('index', 'store');
    Route::resource('manageStandardProps', ManageStandardPropsController::class)->only('index', 'store', 'create');
});
