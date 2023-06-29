<?php

use App\Events\Test;
use App\Http\Controllers\ComponentDemo\ComponentDemo;
use App\Http\Controllers\RedisController;
use App\Http\Controllers\Utils\ParserController;
use App\Http\Controllers\WelcomeCanhController;
use App\Http\Controllers\WelcomeDueController;
use App\Http\Controllers\WelcomeDueController_apple_store_category_per_date;
use App\Http\Controllers\WelcomeDueController_apple_store_draw_row_field;
use App\Http\Controllers\WelcomeDueController_apple_store_product_per_date;
use App\Http\Controllers\WelcomeDueController_apple_store_test_display;
use App\Http\Controllers\WelcomeDueController_hr_timesheet_employee_date;
use App\Http\Controllers\WelcomeDueController_hr_timesheet_employee_project;
use App\Http\Controllers\WelcomeDueController_hr_timesheet_project_date;
use App\Http\Controllers\WelcomeDueController_hr_timesheet_team_date;
use App\Http\Controllers\WelcomeFortuneController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

Route::resource('welcome-canh', WelcomeCanhController::class)->only('index');
Route::get('welcome-canh-all', [WelcomeCanhController::class, 'indexAll'])->name('welcome-canh-all.index');
Route::resource('welcome-due', WelcomeDueController::class)->only('index');

Route::resource('welcome-due-employee_date', WelcomeDueController_hr_timesheet_employee_date::class)->only('index');
Route::resource('welcome-due-employee_project', WelcomeDueController_hr_timesheet_employee_project::class)->only('index');
Route::resource('welcome-due-project_date', WelcomeDueController_hr_timesheet_project_date::class)->only('index');
Route::resource('welcome-due-team_date', WelcomeDueController_hr_timesheet_team_date::class)->only('index');
Route::resource('welcome-due-product_date', WelcomeDueController_apple_store_product_per_date::class)->only('index');
Route::resource('welcome-due-category_date', WelcomeDueController_apple_store_category_per_date::class)->only('index');
Route::resource('welcome-due-apple_store_draw_row_field', WelcomeDueController_apple_store_draw_row_field::class)->only('index');
Route::resource('welcome-due-apple_store_test_display', WelcomeDueController_apple_store_test_display::class)->only('index');

Route::resource('welcome-fortune', WelcomeFortuneController::class)->only('index', 'store');

Route::resource('utils/parser', ParserController::class)->only('index', 'store');

Route::get('components', [ComponentDemo::class, 'index'])->name("components.index");
Route::get('redis', [RedisController::class, 'index']);


Route::get('login/google', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'handleGoogleCallback']);
Route::get('test-websocket', function () {
    broadcast(new Test());
});
