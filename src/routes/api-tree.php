<?php

use App\Http\Controllers\Api\v1\TreeRenderer\PjTaskTreeRendererController;
use App\Http\Controllers\Api\v1\TreeRenderer\PpProcedurePolicyNotifyToTreeRendererController;
use App\Http\Controllers\Api\v1\TreeRenderer\PpProcedurePolicyTreeRendererController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/tree',
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::get("pj_task_tree_explorer", [PjTaskTreeRendererController::class, 'renderToJson'])->name("pj_task_tree_explorer");
    Route::get("pp_procedure_policy_tree_explorer", [PpProcedurePolicyTreeRendererController::class, 'renderToJson'])->name("pp_procedure_policy_tree_explorer");
    Route::get("pp_procedure_policy_notify_to_tree_explorer", [PpProcedurePolicyNotifyToTreeRendererController::class, 'renderToJson'])->name("pp_procedure_policy_notify_to_tree_explorer");
});
