<?php

use App\Http\Controllers\Reports2\Report2Controller;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth', 'impersonate',]
], function () {
    Route::group([
        'prefix' => 'reports'
    ], function () {
        Route::get('v2/{report_slug}', [Report2Controller::class, 'index'])->name('reports.v2');
    });
});
