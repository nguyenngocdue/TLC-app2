<?php

use App\Http\Controllers\Api\v1\HR\LeaveLineController;
use App\Http\Controllers\Api\v1\HR\PublicHolidaysControllerApi;
use App\Http\Controllers\Api\v1\HR\OvertimeRequestLineController;
use App\Http\Controllers\Api\v1\HR\TimeSheetOfficerController;
use App\Http\Controllers\Api\v1\HR\TimeSheetWorkerController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/hr',
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::get('public-holidays-data', [PublicHolidaysControllerApi::class, 'index'])->name('public-holidays-data.index');
    Route::resource('timesheet_officers', TimeSheetOfficerController::class);
    Route::get('timesheet_officers_duplicate/{id}', [TimeSheetOfficerController::class, 'duplicate']);
    Route::resource('timesheet_workers', TimeSheetWorkerController::class);

    Route::post('overtime_request_line2', [OvertimeRequestLineController::class, 'getRemainingHours2']);
    Route::post('leave_line', [LeaveLineController::class, 'getRemainingDays']);
});
// Route::group([
//     'prefix' => 'v1/hr',
//     'middleware' => 'throttle:600,1',
// ], function () {
//     Route::post('overtime_request_line2', [OvertimeRequestLineController::class, 'getRemainingHours2']);
// });
