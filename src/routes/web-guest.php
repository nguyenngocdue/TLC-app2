<?php

use App\Http\Controllers\ComponentDemo\ComponentDemo;
use App\Http\Controllers\HomeWebPage\HomeWebPageController;
use App\Http\Controllers\Reports2\Rp_pageController;
use App\Http\Controllers\Utils\ParserController;
use App\Http\Controllers\WelcomeCanhController;
use App\Http\Controllers\WelcomeDueController;
use App\Http\Controllers\WelcomeFortuneController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
Route::get('/', [HomeWebPageController::class, 'index'])->name('home-web-page.index');

Route::group(['middleware' =>  ['auth', 'impersonate'],], function () {
    Route::resource('welcome-canh', WelcomeCanhController::class)->only('index');
    Route::get('welcome-canh-all', [WelcomeCanhController::class, 'indexAll'])->name('welcome-canh-all.index');

    Route::resource('welcome-due-test-widget', WelcomeDueController::class)->only('index');

    // Route::resource('welcome-due', WelcomeDueController::class)->only('index');
    //Unit tests for reports
    Route::resource('welcome-due/zunit-test-report', WelcomeDueController::class)->only('index');


    Route::resource('welcome-fortune', WelcomeFortuneController::class)->only('index', 'store');
});

Route::resource('utils/parser', ParserController::class)->only('index', 'store');

Route::get('components', [ComponentDemo::class, 'index'])->name("components.index");

Route::get('login/google', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'handleGoogleCallback']);
