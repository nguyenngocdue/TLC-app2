<?php

use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Manage\Media\ManageMediaPropController;
use App\Http\Controllers\Manage\Media\ManageMediaTablePropController;
use App\Http\Controllers\Manage\Post\ManagePostPropController;
use App\Http\Controllers\Manage\Post\ManagePostTablePropController;
use App\Http\Controllers\Manage\User\ManageUserPropController;
use App\Http\Controllers\Manage\User\ManageUserTablePropController;
<<<<<<< HEAD
use App\Http\Controllers\Render\Media\DeleteRenderMediaController;
use App\Http\Controllers\Render\Media\RenderMediaController;
use App\Http\Controllers\Render\Post\RenderPostController;
use App\Http\Controllers\Render\Post\DeleteRenderPostController;
use App\Http\Controllers\Render\User\RenderUserController;
use App\Http\Controllers\Render\User\DeleteRenderUserController;
use App\Http\Controllers\UserEdit;
=======
use App\Http\Controllers\Render\Media\MediaActionRenderController;
use App\Http\Controllers\Render\Media\MediaRenderController;
use App\Http\Controllers\Render\Post\PostActionRenderController;
use App\Http\Controllers\Render\Post\PostRenderController;
use App\Http\Controllers\Render\User\UserActionRenderController;
use App\Http\Controllers\Render\User\UserRenderController;
>>>>>>> fe3ae4d22c9e53a2a565e8bf0367e5e32ecc92fd
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
    'prefix' => 'dashboard'
], function () {
    Route::group([
        'middleware' => 'auth'
    ], function () {
        Route::get('/', [DashBoardController::class, 'index'])->name('dashboard');
<<<<<<< HEAD
        Route::resource('user/user_renderprop', RenderUserController::class);
        Route::resource('user/user_manage', DeleteRenderUserController::class);
        Route::resource('user/user_edit', DeleteRenderUserController::class);
        Route::resource('media/media_renderprop', RenderMediaController::class);
        Route::resource('media/media_manage', DeleteRenderMediaController::class);
        Route::resource('post/post_renderprop', RenderPostController::class);
        Route::resource('post/post_manage', DeleteRenderPostController::class);
=======
        Route::resource('user/user_renderprop', UserRenderController::class);
        Route::resource('user/user_manage', UserActionRenderController::class);
        Route::resource('user/user_edit', UserActionRenderController::class);
        Route::resource('media/media_renderprop', MediaRenderController::class);
        Route::resource('media/media_manage', MediaActionRenderController::class);
        Route::resource('post/post_renderprop', PostRenderController::class);
        Route::resource('post/post_manage', PostActionRenderController::class);
>>>>>>> master
    });
});
Route::group([
    'prefix' => 'propman'
], function () {
    Route::group([
        'middleware' => 'auth'
    ], function () {
        Route::resource('user/user_manageprop', ManageUserPropController::class);
        Route::resource('user/user_managelineprop', ManageUserTablePropController::class);
        Route::resource('media/media_manageprop', ManageMediaPropController::class);
        Route::resource('media/media_managelineprop', ManageMediaTablePropController::class);
        Route::resource('post/post_manageprop', ManagePostPropController::class);
        Route::resource('post/post_managelineprop', ManagePostTablePropController::class);
    });
});
Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);



// Route::get('/dashboard/user/manageprop', [ManageUserController::class, 'manageProp'])->name('user.manageProp');
// Route::post('/dashboard/user/manageprop', [ManageUserController::class, 'manageProp'])->name('user.manageProp');
// Route::get('/dashboard/user/managelineprop', [ManageLineController::class, 'manageLineProp'])->name('user.manageLineProp');
