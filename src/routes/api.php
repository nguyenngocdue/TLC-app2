<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\v1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\v1\System\NotificationsController;
use App\Http\Controllers\Api\v1\System\VersionController;
use App\Http\Controllers\Api\v1\Production\ProductionController;
use App\Http\Controllers\Api\v1\Production\ProductionRunController;
use App\Http\Controllers\Api\v1\FileController;
use App\Http\Controllers\Api\v1\qaqc\SubmitFormAndUploadFileController;
use App\Http\Controllers\Api\v1\qaqc\CommentApiController;
use App\Http\Controllers\Api\v1\qaqc\Qaqc_insp_chklst_sht_sigController;
use App\Http\Controllers\Api\v1\qaqc\Qaqc_insp_chklst_lineController;
use App\Http\Controllers\Api\v1\Auth\SocialiteAuthController;
use App\Http\Controllers\Api\v1\qaqc\RemindSignOffController;
use App\Http\Controllers\Api\v1\qaqc\CloneChklstFromTmpl;
use App\Http\Controllers\Api\v1\HR\TimeSheetOfficerController;
use App\Http\Controllers\Api\v1\HR\TimeSheetWorkerController;
use App\Http\Controllers\Api\v1\HR\OvertimeRequestLineController;

use App\Http\Controllers\Entities\EntityCRUDControllerForApi;
use App\Http\Controllers\Entities\EntityCRUDControllerForApiRenderer;
use App\Http\Controllers\Workflow\LibApis;
use App\Utils\System\Memory;
use App\Utils\System\Timer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Timer::startTimeCounter();
Memory::startMemoryCounter();
Route::group([
    'prefix' => 'v1/auth',
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'register']);
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'tokenResetPassword']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::get('verify', [AuthController::class, 'verify']);
    });
});
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
Route::group([
    'prefix' => 'v1/qaqc',
], function () {
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::post('upload_file', [FileController::class, 'upload']);
        Route::post('delete_file', [FileController::class, 'delete']);
        Route::post('edit_file', [FileController::class, 'edit']);
        // Route::post('submit_form', [App\Http\Controllers\Api\v1\qaqc\SubmitFormAndUploadFileController::class, 'submitAndUploadFile']);
        Route::post('submit_form2', [SubmitFormAndUploadFileController::class, 'submit2AndUploadFile']);
        Route::get('qaqc_insp_chklst_line/{id}', [CommentApiController::class, 'getAll']);
        Route::post('comment', [CommentApiController::class, 'store']);
        Route::delete('comment/{id}', [CommentApiController::class, 'destroy']);
        Route::post('signature', [Qaqc_insp_chklst_sht_sigController::class, 'store']);
        Route::delete('signature/{id}', [Qaqc_insp_chklst_sht_sigController::class, 'destroy']);
        Route::put('signature/{id}', [Qaqc_insp_chklst_sht_sigController::class, 'update']);
        Route::get("{id}/href_ncr", [Qaqc_insp_chklst_lineController::class, 'getHrefFormNcr']);
    });
});
Route::group([
    'prefix' => 'v1/login',
], function () {
    Route::get('google', [SocialiteAuthController::class, 'redirectToGoogle'])->name('google_auth');
    Route::get('google/callback', [SocialiteAuthController::class, 'handleGoogleCallback']);
});
Route::group([
    'prefix' => 'v1/system',
], function () {
    Route::get('app_version', [VersionController::class, 'version']);
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('notifications', [NotificationsController::class, 'notifications']);
        Route::get('notificationsRender', [NotificationsController::class, 'notificationsRender']);
    });
});
Route::group([
    'prefix' => 'v1/qaqc',
    // 'middleware' => 'auth'
], function () {
    Route::post("remind_sign_off", [RemindSignOffController::class, 'remind']);
    Route::post("clone_chklst_from_tmpl", [CloneChklstFromTmpl::class, 'clone']);
});
Route::group([
    'prefix' => 'v1/hr',
    'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    Route::resource('timesheet_officers', TimeSheetOfficerController::class);
    Route::get('timesheet_officers_duplicate/{id}', [TimeSheetOfficerController::class, 'duplicate']);
    Route::resource('timesheet_workers', TimeSheetWorkerController::class);
});
Route::group([
    'prefix' => 'v1/hr',
    'middleware' => 'throttle:600,1',
], function () {
    Route::post('overtime_request_line2', [OvertimeRequestLineController::class, 'getRemainingHours2']);
});
Route::group([
    'prefix' => 'v1/entity',
    'middleware' => ['throttle:600,1'],
    // 'middleware' => ['auth:sanctum', 'throttle:600,1'],
], function () {
    $apps = LibApis::getFor('storeEmpty_and_updateShort');
    foreach ($apps as $tableName) {
        Route::post("{$tableName}_storeEmpty", [EntityCRUDControllerForApi::class, 'storeEmpty'])->name($tableName . ".storeEmpty");
        Route::post("{$tableName}_updateShort", [EntityCRUDControllerForApi::class, 'updateShort'])->name($tableName . ".updateShort");
    }
});
Route::group([
    'prefix' => 'v1/entity',
    'middleware' => 'throttle:600,1'
], function () {
    $apps = LibApis::getFor('renderTableForPopupModals');
    foreach ($apps as $tableName) {
        Route::post("{$tableName}_renderTable", [EntityCRUDControllerForApiRenderer::class, 'renderTable'])->name($tableName . ".renderTable");
    }
});
