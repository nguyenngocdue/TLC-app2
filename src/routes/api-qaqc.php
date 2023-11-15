<?php

use App\Http\Controllers\Api\v1\FileController;
use App\Http\Controllers\Api\v1\qaqc\CommentApiController;
use App\Http\Controllers\Api\v1\qaqc\Qaqc_insp_chklst_lineController;
use App\Http\Controllers\Api\v1\qaqc\Qaqc_insp_chklst_sht_sigController;
use App\Http\Controllers\Api\v1\qaqc\RequestSignOffController;
use App\Http\Controllers\Api\v1\qaqc\SubmitFormAndUploadFileController;
use App\Http\Controllers\Api\v1\qaqc\WirLineController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/qaqc',
], function () {
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::post('upload_file', [FileController::class, 'upload']);
        Route::post('delete_file', [FileController::class, 'delete']);
        Route::post('edit_file', [FileController::class, 'edit']);
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
    'prefix' => 'v1/qaqc',
    'middleware' => ['auth:sanctum'],
    // 'middleware' => 'auth'
], function () {
    // Route::post("remind_sign_off", [RemindSignOffController::class, 'remind']);
    // Route::post("clone_chklst_from_tmpl", [CloneTemplateForQaqcChecklistController::class, 'clone']);
    Route::post("request_to_sign_off", [RequestSignOffController::class, 'requestSignOff']);
});


Route::group([
    'prefix' => 'v1/qaqc',
    'middleware' => 'throttle:6000,1',
], function () {
    Route::post('wir_line', [WirLineController::class, 'getRemaining']);
});
