<?php

use App\Http\Controllers\Reports2\UserReportSettingController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth', 'impersonate',]
], function () {
    Route::group([
        'prefix' => 'reports'
    ], function () {
        Route::get('manageReports', [UserReportSettingController::class, 'index'])->name('rp_manage_reports');
        Route::post('manageReports/updateUserSetting', [UserReportSettingController::class, 'update'])->name('rp_reset_user_setting');
    });
});
