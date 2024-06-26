<?php

use App\Http\Controllers\Workflow\ManageApisController;
use App\Http\Controllers\Workflow\ManageAppCreationsController;
use App\Http\Controllers\Workflow\ManageAppsController;
use App\Http\Controllers\Workflow\ManageBlockReportsController;
use App\Http\Controllers\Workflow\ManageColumnReportsController;
use App\Http\Controllers\Workflow\ManageEditableTablesController;
use App\Http\Controllers\Workflow\ManageListenerActionsController;
use App\Http\Controllers\Workflow\ManageNavbarsController;
use App\Http\Controllers\Workflow\ManagePivotTablesController;
use App\Http\Controllers\Workflow\ManageProfileFieldsController;
use App\Http\Controllers\Workflow\ManageReportsController;
use App\Http\Controllers\Workflow\ManageStandardDefaultValuesController;
use App\Http\Controllers\Workflow\ManageStandardPropsController;
use App\Http\Controllers\Workflow\ManageStatusesController;
use App\Http\Controllers\Workflow\ManageTypeOfReportsController;
use App\Http\Controllers\Workflow\ManageWidgetsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'dashboard/workflow',
    // 'middleware' => ['auth'],
    'middleware' => ['role_set:admin', 'impersonate']
], function () {
    Route::resource('manageStatuses', ManageStatusesController::class)->only('index', 'store', 'create');
    Route::resource('manageWidgets', ManageWidgetsController::class)->only('index', 'store', 'create');
    Route::resource('managePivotTables', ManagePivotTablesController::class)->only('index', 'store', 'create');
    Route::resource('manageApps', ManageAppsController::class)->only('index', 'store', 'create');
    Route::resource('manageReports', ManageReportsController::class)->only('index', 'store', 'create');

    Route::resource('manageColumnReports', ManageColumnReportsController::class)->only('index', 'store', 'create');
    Route::resource('manageTypeOfReports', ManageTypeOfReportsController::class)->only('index', 'store', 'create');
    Route::resource('manageBlockReports', ManageBlockReportsController::class)->only('index', 'store', 'create');

    Route::resource('manageApis', ManageApisController::class)->only('index', 'store', 'create');
    Route::resource('manageNavbars', ManageNavbarsController::class)->only('index', 'store', 'create');
    Route::resource('manageProfileFields', ManageProfileFieldsController::class)->only('index', 'store');
    Route::resource('manageAppCreations', ManageAppCreationsController::class)->only('index', 'store');
    Route::resource('manageStandardProps', ManageStandardPropsController::class)->only('index', 'store', 'create');
    Route::resource('manageStandardDefaultValues', ManageStandardDefaultValuesController::class)->only('index', 'store', 'create');
    Route::resource('manageListenerActions', ManageListenerActionsController::class)->only('index', 'store', 'create');
    Route::resource('manageEditableTables', ManageEditableTablesController::class)->only('index', 'store', 'create');
});
