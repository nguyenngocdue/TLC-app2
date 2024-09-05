<?php

use App\Http\Controllers\ComponentDemo\ComponentDemo;
use App\Http\Controllers\HomeWebPage\HomeWebPageController;
use App\Http\Controllers\Utils\ParserController;
use App\Http\Controllers\WelcomeCanhController;
use App\Http\Controllers\WelcomeDueController;
use App\Http\Controllers\WelcomeFortuneController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
Route::get('/', [HomeWebPageController::class, 'indexModuQa'])->name('home-web-page.indexModuQa');
Route::get('/home/company', [HomeWebPageController::class, 'indexCompany'])->name('home-web-page.indexCompany');
Route::get('/home/quicklink', [HomeWebPageController::class, 'indexQuickLink'])->name('home-web-page.indexQuickLink');

Route::group(['middleware' =>  ['auth', 'impersonate'],], function () {
    Route::resource('welcome-canh', WelcomeCanhController::class)->only('index');
    Route::get('welcome-canh-all', [WelcomeCanhController::class, 'indexAll'])->name('welcome-canh-all.index');
    Route::resource('welcome-due', WelcomeDueController::class)->only('index');
    Route::resource('welcome-fortune', WelcomeFortuneController::class)->only('index', 'store');
});

Route::resource('utils/parser', ParserController::class)->only('index', 'store');

Route::get('components', [ComponentDemo::class, 'index'])->name("components.index");

Route::get('login/google', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'handleGoogleCallback']);
