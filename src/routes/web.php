<?php

use App\Http\Controllers\MailController;
use App\Http\Controllers\UploadFileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return view("welcome");
});
Route::get('/phpinfo', function () {
    return phpinfo();
});
// Auth::routes([
//     'reset' => false,
//     'verify' => false,
//     'register' => false,
// ]);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/mail-test', [MailController::class, 'index']);

Route::post('/mail-test', [MailController::class, 'sendMail'])->name('send_mail');

Route::resource('/uploadfiles', UploadFileController::class);

Route::get('/uploadfiles/{id}/download', [UploadFileController::class, 'download'])->name('uploadfiles.download');


Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
