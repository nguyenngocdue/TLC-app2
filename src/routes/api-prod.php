<?php

use App\Http\Controllers\Api\v1\Production\ProductionController;
use App\Http\Controllers\Api\v1\Production\ProductionRunController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/prod',
], function () {
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('sub_projects', [ProductionController::class, 'getSubProjects']);
        Route::get('prod_run_line/data', [ProductionController::class, 'getDataProductionLine']);
        Route::get('sub_projects/{id}', [ProductionController::class, 'getProdOrders']);
        Route::get('sub_projects/{id1}/prod_orders/{id2}', [ProductionController::class, 'getProdOrders']);
        Route::resource("prod_run", ProductionRunController::class);
        Route::put("prod_run/stopped/{prod_run_id}", [ProductionRunController::class, 'stopped'])->name('prod_run.stopped');
        // Route::resource("prod_run_line", App\Http\Controllers\Api\v1\Production\ProductionRunLineController::class);
        // Route::get("prod_run_line/duplicate/{id}", [App\Http\Controllers\Api\v1\Production\ProductionRunLineController::class, 'duplicate'])->name('prod_run_line.duplicate');
        // Route::get("prod_run_line/{sub_project_id}/{prod_order_id}/{prod_routing_link_id}", [App\Http\Controllers\Api\v1\Production\ProductionRunLineController::class, 'prodLine'])->name('prod_run.live');
    });
});
