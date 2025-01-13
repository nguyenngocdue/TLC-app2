<?php

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
], function () {
    Route::get('getStatuses/{entityType}', [LibStatuses::class, 'getFor']);
    Route::get('getStatuses', [LibStatuses::class, 'getAll']);
    Route::get('getApps', [LibApps::class, 'getAll']);
});
