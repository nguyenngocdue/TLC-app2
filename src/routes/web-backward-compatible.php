<?php

use App\Http\Controllers\Conqa\ConqaArchiveController;
use Illuminate\Support\Facades\Route;
//This is for back compatible with TLC App 1, QR Code scannings
Route::get('/modular/{slug}',  fn ($slug) => redirect('dashboard/pj_modules/' . $slug));
Route::get('/unit/{slug}', fn ($slug) => redirect('dashboard/pj_units/' . $slug));
Route::get('/shipment/{slug}', fn ($slug) => redirect('dashboard/pj_shipments/' . $slug));

Route::get('/conqa_archive/{type}/{name}/{uuid}', [ConqaArchiveController::class, 'index'])->name("conqa-archive");
