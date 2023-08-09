<?php

use App\Events\Test;
use App\Events\WssDemoChannel;
use App\Http\Controllers\ComponentDemo\ComponentDemo;
use App\Http\Controllers\RedisController;
use App\Http\Controllers\Utils\ParserController;
use App\Http\Controllers\WelcomeCanhController;
use App\Http\Controllers\WelcomeDueController;
use App\Http\Controllers\WelcomeFortuneController;
use App\Jobs\TestLogToFileJob;
use App\Warehouse\Wh_report_data_1s;
use App\Warehouse\Wh_user_sub_project_task;
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

Route::resource('welcome-canh', WelcomeCanhController::class)->only('index');
Route::get('welcome-canh-all', [WelcomeCanhController::class, 'indexAll'])->name('welcome-canh-all.index');
Route::resource('welcome-due', WelcomeDueController::class)->only('index');

// Report from dataWarehouse
Route::resource('welcome-due-dataWarehouse', Wh_user_sub_project_task::class)->only('index');
Route::resource('welcome-due-dataWarehouse1', Wh_report_data_1s::class)->only('index');

Route::resource('welcome-fortune', WelcomeFortuneController::class)->only('index', 'store');

Route::resource('utils/parser', ParserController::class)->only('index', 'store');

Route::get('components', [ComponentDemo::class, 'index'])->name("components.index");
Route::get('redis', [RedisController::class, 'index']);


Route::get('login/google', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'handleGoogleCallback']);
Route::get('wss-demo', function () {
    broadcast(new WssDemoChannel(['name' => 'wss-demo']));
});

Route::get('test-queue', function () {
    TestLogToFileJob::dispatch();
});
