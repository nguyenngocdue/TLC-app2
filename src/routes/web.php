<?php

use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Manage\Media\ManageMediaController;
use App\Http\Controllers\Manage\Media\ManageMediaLineController;
use App\Http\Controllers\Manage\User\ManageUserController;
use App\Http\Controllers\Manage\User\ManageUserLineController;
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

// Auth::routes([
//     'reset' => false,
//     'verify' => false,
//     'register' => false,
// ]);
Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::group([
    'prefix' =>'dashboard'
],function(){
    Route::group([
        'middleware' => 'auth'
    ],function(){
        Route::get('/', [DashBoardController::class, 'index'])->name('dashboard');
        Route::resource('user/user_manageprop',ManageUserController::class);
        Route::resource('user/user_managelineprop',ManageUserLineController::class);
        Route::resource('media/media_manageprop',ManageMediaController::class);
        Route::resource('media/media_managelineprop',ManageMediaLineController::class);
    });
});
Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);


// Route::get('/dashboard/user/manageprop', [ManageUserController::class, 'manageProp'])->name('user.manageProp');
// Route::post('/dashboard/user/manageprop', [ManageUserController::class, 'manageProp'])->name('user.manageProp');
// Route::get('/dashboard/user/managelineprop', [ManageLineController::class, 'manageLineProp'])->name('user.manageLineProp');
