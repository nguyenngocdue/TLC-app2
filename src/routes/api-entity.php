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
    $apps = LibApis::getFor('storeEmpty_and_updateShort');
    foreach ($apps as $tableName) {
        Route::post("{$tableName}_storeEmpty", [EntityCRUDControllerForApi::class, 'storeEmpty'])->name($tableName . ".storeEmpty");
        Route::post("{$tableName}_updateShort", [EntityCRUDControllerForApi::class, 'updateShort'])->name($tableName . ".updateShort");
    }

    $apps = LibApis::getFor('changeStatusMultiple');
    foreach ($apps as $tableName) {
        Route::post("{$tableName}_changeStatusMultiple", [EntityCRUDControllerForApi::class, 'changeStatusMultiple'])->name($tableName . ".changeStatusMultiple");
    }

    $apps = LibApis::getFor('renderTableForPopupModals');
    foreach ($apps as $tableName) {
        Route::post("{$tableName}_renderTable", [EntityCRUDControllerForApiRenderer::class, 'renderTable'])->name($tableName . ".renderTable");
    }

    $apps = LibApis::getFor('cloneTemplate_and_updateShort');
    foreach ($apps as $tableName) {
        Route::post("{$tableName}_cloneTemplate", [EntityCRUDControllerForApiClone::class, 'cloneTemplate'])->name($tableName . ".cloneTemplate");
    }
});
