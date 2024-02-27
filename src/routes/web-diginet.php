<?php

use App\Http\Controllers\DiginetHR\TransferDataDiginetToApp;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['auth', 'impersonate',]
], function () {
    Route::get('transfer-data-diginet', [TransferDataDiginetToApp::class, 'index']);
});
