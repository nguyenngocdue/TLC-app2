<?php

use App\Http\Controllers\Entities\EntityCRUDControllerForApi;
use App\Http\Controllers\Entities\EntityCRUDControllerForApiClone;
use App\Http\Controllers\Entities\EntityCRUDControllerForApiRenderer;
use App\Http\Controllers\Workflow\LibApis;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/entity',
    // 'middleware' => ['throttle:600,1'],
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    $apps = LibApis::getAll();
    foreach ($apps as $app) {
        $tableName = $app['name'];
        if ($app['storeEmpty_and_updateShort'] ?? false) Route::post("{$tableName}_storeEmpty", [EntityCRUDControllerForApi::class, 'storeEmpty'])->name($tableName . ".storeEmpty");
        if ($app['storeEmpty_and_updateShort'] ?? false) Route::post("{$tableName}_updateShort", [EntityCRUDControllerForApi::class, 'updateShort'])->name($tableName . ".updateShort");
        if ($app['changeStatusMultiple'] ?? false) Route::post("{$tableName}_changeStatusMultiple", [EntityCRUDControllerForApi::class, 'changeStatusMultiple'])->name($tableName . ".changeStatusMultiple");
        if ($app['getLines'] ?? false) Route::post("{$tableName}_getLines", [EntityCRUDControllerForApi::class, 'getLines'])->name($tableName . ".getLines");

        if ($app['renderTableForPopupModals'] ?? false) Route::post("{$tableName}_renderTable", [EntityCRUDControllerForApiRenderer::class, 'renderTable'])->name($tableName . ".renderTable");
        if ($app['cloneTemplate_and_updateShort'] ?? false) Route::post("{$tableName}_cloneTemplate", [EntityCRUDControllerForApiClone::class, 'cloneTemplate'])->name($tableName . ".cloneTemplate");
    }
});
