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
    Route::get('getCurrentUser', function () {
        return [
            "profile" => [
                "name" => "Administrator 0",
                "description" => "System Admin",
                "avatarSrc" => "https://minio.tlcmodular.com/tlc-app/avatars/admin avatar-150x150.png",
            ],
            "selectedGlobalProjectId" => 15,
        ];
    });
    Route::get('getProjects', function () {
        return Project::query()

            ->whereIn('status', [
                'manufacturing',
                'construction_site',
                'design',
                // 'concept',
            ])
            ->with(['getAvatar' => function ($q) {
                $q->select('id', 'url_thumbnail', 'attachments.object_type', 'attachments.object_id');
            }])
            ->orderBy('name')
            ->get();
    });
});
