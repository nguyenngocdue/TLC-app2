<?php

use App\Http\Controllers\Entities\EntityCRUDControllerForApi;
use App\Http\Controllers\Entities\EntityCRUDControllerForApiClone;
use App\Http\Controllers\Entities\EntityCRUDControllerForApiRenderer;
use App\Http\Controllers\Workflow\LibApis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/entity',
    // 'middleware' => ['throttle:600,1'],
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    $apps = LibApis::getAll();
    foreach ($apps as $app) {
        $tableName = $app['name'];
        if ($app['createNewShort'] ?? false) Route::post("{$tableName}_createNewShort", [EntityCRUDControllerForApi::class, 'createNewShort'])->name($tableName . ".createNewShort");
        if ($app['updateShortSingle'] ?? false) Route::post("{$tableName}_updateShortSingle", [EntityCRUDControllerForApi::class, 'updateShortSingle'])->name($tableName . ".updateShortSingle");

        if ($app['storeEmpty_and_updateShort'] ?? false) Route::post("{$tableName}_storeEmpty", [EntityCRUDControllerForApi::class, 'storeEmpty'])->name($tableName . ".storeEmpty");
        if ($app['storeEmpty_and_updateShort'] ?? false) Route::post("{$tableName}_updateShort", [EntityCRUDControllerForApi::class, 'updateShort'])->name($tableName . ".updateShort");
        if ($app['changeStatusMultiple'] ?? false) Route::post("{$tableName}_changeStatusMultiple", [EntityCRUDControllerForApi::class, 'changeStatusMultiple'])->name($tableName . ".changeStatusMultiple");
        if ($app['searchable'] ?? false) Route::get("{$tableName}_searchable", [EntityCRUDControllerForApi::class, 'searchable'])->name($tableName . ".searchable");
        if ($app['cloneTemplate_and_updateShort'] ?? false) Route::post("{$tableName}_cloneTemplate", [EntityCRUDControllerForApiClone::class, 'cloneTemplate'])->name($tableName . ".cloneTemplate");
        // if ($app['getLines'] ?? false) Route::post("{$tableName}_getLines", [EntityCRUDControllerForApi::class, 'getLines'])->name($tableName . ".getLines");

        if ($app['renderTableForPopupModals'] ?? false) Route::post("{$tableName}_renderTable", [EntityCRUDControllerForApiRenderer::class, 'renderTable'])->name($tableName . ".renderTable");

        if (in_array($tableName, [
            'kanban_task_clusters',
            'kanban_task_groups',
            'kanban_tasks',
            'kanban_task_pages',
            'kanban_task_buckets',
        ])) {
            Route::post("{$tableName}_kanban", [EntityCRUDControllerForApi::class, 'kanban'])->name($tableName . ".kanban");
        }
    }
});

Route::group([
    'prefix' => 'v1/custom_modal',
    // 'middleware' => ['throttle:600,1'],
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::get("user_get_line_service", [App\Http\Services\GetLineForModal\UserGetLineService::class, 'getLines'])->name("user_get_line_service");
    Route::get("travel_detail_get_line_service", [App\Http\Services\GetLineForModal\TravelDetailGetLineService::class, 'getLines'])->name("travel_detail_get_line_service");
    Route::get("advance_detail_get_line_service", [App\Http\Services\GetLineForModal\AdvanceDetailGetLineService::class, 'getLines'])->name("advance_detail_get_line_service");
    Route::get("block_detail_get_line_service", [App\Http\Services\GetLineForModal\BlockDetailGetLineService::class, 'getLines'])->name("block_detail_get_line_service");
});
