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
use App\Mail\MailTest;
use App\Warehouse\Wh_report_data_1s;
use App\Warehouse\Wh_user_sub_project_task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

Route::resource('welcome-canh', WelcomeCanhController::class)->only('index');
Route::get('welcome-canh-all', [WelcomeCanhController::class, 'indexAll'])->name('welcome-canh-all.index');
Route::resource('welcome-due-test-widget', WelcomeDueController::class)->only('index');
Route::resource('welcome-due', WelcomeDueController::class)->only('index');

Route::resource('welcome-fortune', WelcomeFortuneController::class)->only('index', 'store');

Route::resource('utils/parser', ParserController::class)->only('index', 'store');

Route::get('components', [ComponentDemo::class, 'index'])->name("components.index");
Route::get('redis', [RedisController::class, 'index']);


Route::get('login/google', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'handleGoogleCallback']);
Route::get('test-wss', function () {
    broadcast(new WssDemoChannel(['name' => 'wss-demo-822553']));
});

Route::get('test-queue', function () {
    TestLogToFileJob::dispatch();
});
Route::get('test-mail', function (Request $request) {
    if (!$request->has('email')) return 'Please enter your email address on url params';
    $email = $request->input('email');
    try {
        Mail::to($email)->send(new MailTest());
    } catch (\Exception $e) {
        return "Mail Failed to send. Message: " . $e->getMessage();
    }
    return 'Test Mail Successful! Please check email test in mail ' . $email;
});
