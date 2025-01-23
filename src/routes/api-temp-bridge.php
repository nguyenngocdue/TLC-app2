<?php

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Controllers\Workflow\LibNavbars;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
], function () {
    Route::get('getStatuses/{entityType}', [LibStatuses::class, 'getFor']);
    Route::get('getStatuses', [LibStatuses::class, 'getAll']);
    Route::get('getApps', [LibApps::class, 'getAll']);
    Route::get('getAdminMenu', [LibNavbars::class, 'getUserMenu']);
    Route::get('getProjects', function () {
        return Project::query()

            ->where('status', ['manufacturing', 'construction_site', 'design'])
            ->with(['getAvatar' => function ($q) {
                $q->select('id', 'url_thumbnail', 'attachments.object_type', 'attachments.object_id');
            }])
            ->orderBy('name')
            ->get();
    });
});
