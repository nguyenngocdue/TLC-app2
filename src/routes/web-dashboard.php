<?php

use App\Http\Controllers\Entities\EntityCRUDController;
use App\Http\Controllers\Entities\ViewAllController;
use App\Http\Controllers\Entities\ViewAllInvokerController;
use App\Utils\Support\Entities;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::group([
    'middleware' => ['auth', 'impersonate',]
], function () {
    Route::group([
        'prefix' => 'dashboard'
    ], function () {
        $entities = Entities::getAll();
        foreach ($entities as $entity) {
            $entityName = Str::getEntityName($entity);
            Route::resource("{$entityName}", ViewAllController::class)->only('index');
            Route::resource("{$entityName}", EntityCRUDController::class)->only('create', 'store', 'edit', 'update', 'show', 'destroy');
            Route::delete("{$entityName}", [EntityCRUDController::class, "destroyMultiple"])->name("{$entityName}.destroyMultiple");

            Route::get("{$entityName}_dp/{id}", [ViewAllInvokerController::class, "duplicate"])->name("{$entityName}_dp");
            Route::post("{$entityName}_dp", [ViewAllInvokerController::class, "duplicateMultiple"])->name("{$entityName}_dp.duplicateMultiple");
            Route::get("{$entityName}_ep", [ViewAllInvokerController::class, "exportCSV"])->name("{$entityName}_ep.exportCSV");
            Route::get("{$entityName}_qr", [ViewAllInvokerController::class, "showQRList6"])->name("{$entityName}_qr.showQRList6");
        }
    });
});
