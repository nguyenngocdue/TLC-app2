<?php

use Illuminate\Support\Facades\Route;
//This is for back compatible with TLC App 1, QR Code scannings
Route::get('/modular/{slug}',  fn ($slug) => redirect('app/pj_modules/' . $slug));
Route::get('/unit/{slug}', fn ($slug) => redirect('app/pj_units/' . $slug));
Route::get('/shipment/{slug}', fn ($slug) => redirect('app/pj_shipments/' . $slug));
