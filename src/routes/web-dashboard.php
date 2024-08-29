<?php

use App\Http\Controllers\Entities\EntityCRUDController;
use App\Http\Controllers\Entities\ViewAllController;
use App\Http\Controllers\Entities\ViewAllInvokerController;
use App\Http\Controllers\Reports2\RefreshReportController;
use App\Http\Controllers\Reports2\Rp_reportController;
use App\Utils\Support\Entities;
use App\Utils\Support\JsonControls;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::group([], function () {
    $entities = Entities::getAll();
    $apps_have_qr = JsonControls::getAppsHaveQrLandingPage();
    foreach ($entities as $entity) {
        $entityName = Str::getEntityName($entity);
        $entitySingular = Str::singular($entityName);
        $middleware = (in_array($entitySingular, $apps_have_qr)) ? [] : ['auth', 'impersonate'];
        Route::group([
            'prefix' => 'dashboard',
            'middleware' => $middleware,
        ], function () use ($entityName, $entitySingular) {
            Route::resource("{$entityName}", ViewAllController::class)->only('index');
            Route::get("{$entityName}_trashed", [ViewAllController::class, 'indexTrashed'])->name("{$entityName}.trashed");
            Route::get("{$entityName}_trashed/{{$entitySingular}}", [EntityCRUDController::class, 'showTrashed'])->name("{$entityName}.showTrashed");
            Route::get("{$entityName}/{{$entitySingular}}/show_calender", [EntityCRUDController::class, 'showCalender'])->name("{$entityName}.showCalender");
            Route::resource("{$entityName}", EntityCRUDController::class)->only('create', 'store', 'edit', 'update', 'show', 'destroy');
            Route::delete("{$entityName}", [EntityCRUDController::class, "destroyMultiple"])->name("{$entityName}.destroyMultiple");

            Route::post("rp_reports/update_filters/{id}", [Rp_reportController::class, 'updateFilters'])->name('report_filters.update');
            Route::post("rp_reports/update_perPages", [Rp_reportController::class, 'updatePerPages'])->name('report_perPages.update');

            Route::post("{$entityName}_rs", [EntityCRUDController::class, "restoreMultiple"])->name("{$entityName}.restoreMultiple");
            Route::post("{$entityName}_dp", [ViewAllInvokerController::class, "duplicateMultiple"])->name("{$entityName}_dp.duplicateMultiple");

            Route::get("{$entityName}_ep", [ViewAllInvokerController::class, "exportCSV"])->name("{$entityName}_ep.exportCSV");
            Route::get("{$entityName}_mep1", [ViewAllInvokerController::class, "exportCsvViewAllMatrix"])->name("{$entityName}_mep1.exportCsvMatrix1");
            Route::get("{$entityName}_mep2", [ViewAllInvokerController::class, "exportCsvMatrixForReport"])->name("{$entityName}_mep2.exportCsvMatrix2");

            Route::put("{$entityName}_prt", [ViewAllInvokerController::class, "print"])->name("{$entityName}_prt.print");
            Route::get("{$entityName}_qr", [ViewAllInvokerController::class, "showQRList6"])->name("{$entityName}_qr.showQRList6");
            Route::get("{$entityName}_prt", [ViewAllInvokerController::class, "printAll"])->name("{$entityName}_prt.printAll");
        });
    }
});
