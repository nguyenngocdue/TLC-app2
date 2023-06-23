<?php

use App\Http\Controllers\Dev\DatabaseDiagramsController;
use App\Http\Controllers\Dev\DatabaseSummaryController;
use Illuminate\Support\Facades\Route;

Route::group([
    // 'middleware' => ['auth'],
    'prefix' => 'database',
    'middleware' => ['role_set:admin']
], function () {
    Route::get('summary', [DatabaseSummaryController::class, 'index'])->name("database-summary.index");
    Route::get('diagrams', [DatabaseDiagramsController::class, 'index'])->name("database-diagrams.index");
});
