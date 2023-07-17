<?php

use App\Events\Test;
use App\Http\Controllers\ComponentDemo\ComponentDemo;
use App\Http\Controllers\RedisController;
use App\Http\Controllers\Utils\ParserController;
use App\Http\Controllers\WelcomeCanhController;
use App\Http\Controllers\WelcomeDueController;
use App\Http\Controllers\WelcomeDueController_apple_store_category_per_date;
use App\Http\Controllers\WelcomeDueController_apple_store_delete_fields_database;
use App\Http\Controllers\WelcomeDueController_apple_store_duplicate_field_column_field_value_index_field;
use App\Http\Controllers\WelcomeDueController_apple_store_duplicate_value_in_column_field;
use App\Http\Controllers\WelcomeDueController_apple_store_duplicate_value_in_column_field_many_objects;
use App\Http\Controllers\WelcomeDueController_apple_store_empty_input;
use App\Http\Controllers\WelcomeDueController_apple_store_empty_row_field;
use App\Http\Controllers\WelcomeDueController_apple_store_minimum_quantity_column_field;
use App\Http\Controllers\WelcomeDueController_apple_store_minimum_quantity_column_field_equal_3;
use App\Http\Controllers\WelcomeDueController_apple_store_product_per_date;
use App\Http\Controllers\WelcomeDueController_apple_store_test_code;
use App\Http\Controllers\WelcomeDueController_hr_timesheet_employee_date;
use App\Http\Controllers\WelcomeDueController_hr_timesheet_employee_project;
use App\Http\Controllers\WelcomeDueController_hr_timesheet_employee_project_change_data_field_title;
use App\Http\Controllers\WelcomeDueController_hr_timesheet_employee_project_not_table_information;
use App\Http\Controllers\WelcomeDueController_hr_timesheet_project_date;
use App\Http\Controllers\WelcomeDueController_hr_timesheet_team_date;
use App\Http\Controllers\WelcomeFortuneController;
use App\View\Components\Renderer\Report\PivotTables\PivotReport_Hr_timesheet_line_project_date;
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

Route::resource('welcome-canh', WelcomeCanhController::class)->only('index');
Route::get('welcome-canh-all', [WelcomeCanhController::class, 'indexAll'])->name('welcome-canh-all.index');
Route::resource('welcome-due', WelcomeDueController::class)->only('index');

Route::resource('welcome-due-employee_date', WelcomeDueController_hr_timesheet_employee_date::class)->only('index');
Route::resource('welcome-due-employee_project', WelcomeDueController_hr_timesheet_employee_project::class)->only('index');
Route::resource('welcome-due-project_date', WelcomeDueController_hr_timesheet_project_date::class)->only('index');
Route::resource('welcome-due-team_date', WelcomeDueController_hr_timesheet_team_date::class)->only('index');
Route::resource('welcome-due-apple_store_product_per_date', WelcomeDueController_apple_store_product_per_date::class)->only('index');
Route::resource('welcome-due-apple_store_category_per_date', WelcomeDueController_apple_store_category_per_date::class)->only('index');
Route::resource('welcome-due-apple_store_delete_fields_database', WelcomeDueController_apple_store_delete_fields_database::class)->only('index');
Route::resource('welcome-due-apple_store_empty_row_field', WelcomeDueController_apple_store_empty_row_field::class)->only('index');
Route::resource('welcome-due-apple_store_minimum_quantity_column_field', WelcomeDueController_apple_store_minimum_quantity_column_field::class)->only('index');
Route::resource('welcome-due-apple_store_duplicate_value_in_column_field', WelcomeDueController_apple_store_duplicate_value_in_column_field::class)->only('index');
Route::resource('welcome-due-apple_store_duplicate_field_column_field_value_index_field', WelcomeDueController_apple_store_duplicate_field_column_field_value_index_field::class)->only('index');
Route::resource('welcome-due-apple_store_duplicate_value_in_column_field_many_objects', WelcomeDueController_apple_store_duplicate_value_in_column_field_many_objects::class)->only('index');
Route::resource('welcome-due-empty_input', WelcomeDueController_apple_store_empty_input::class)->only('index');
// Route::resource('welcome-due-apple_store_minimum_quantity_column_field_equal_3', WelcomeDueController_apple_store_minimum_quantity_column_field_equal_3::class)->only('index');
Route::resource('welcome-due-employee_project_not_table_information', WelcomeDueController_hr_timesheet_employee_project_not_table_information::class)->only('index');
Route::resource('welcome-due-employee_project_change_data_field_title', WelcomeDueController_hr_timesheet_employee_project_change_data_field_title::class)->only('index');
Route::resource('welcome-due-apple_store_test_code', WelcomeDueController_apple_store_test_code::class)->only('index');
// Pivot table
Route::get('pivot/report-hr_timesheet_line_project_date', [PivotReport_Hr_timesheet_line_project_date::class, 'index'])->name('report-hr_timesheet_line_project_date');





Route::resource('welcome-fortune', WelcomeFortuneController::class)->only('index', 'store');

Route::resource('utils/parser', ParserController::class)->only('index', 'store');

Route::get('components', [ComponentDemo::class, 'index'])->name("components.index");
Route::get('redis', [RedisController::class, 'index']);


Route::get('login/google', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\SocialiteAuthController::class, 'handleGoogleCallback']);
Route::get('test-websocket', function () {
    broadcast(new Test());
});
