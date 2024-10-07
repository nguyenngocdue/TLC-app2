<?php

use App\Http\Controllers\Api\v1\TreeRenderer\PjTaskRendererController;
use App\Http\Controllers\Api\v1\TreeRenderer\PpProcedurePolicyController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/hr',
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::get("pj_task_tree_explorer", [PjTaskRendererController::class, 'renderToJson'])->name("pj_task_tree_explorer");
    Route::get("pp_procedure_policy_tree_explorer", [PpProcedurePolicyController::class, 'renderToJson'])->name("pp_procedure_policy_tree_explorer");
});
