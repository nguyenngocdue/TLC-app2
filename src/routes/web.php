<?php

use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Manage\Media\ManageMediaPropController;
use App\Http\Controllers\Manage\Media\ManageMediaRelationshipController;
use App\Http\Controllers\Manage\Media\ManageMediaTablePropController;
use App\Http\Controllers\Manage\Workplace\ManageWorkplacePropController;
use App\Http\Controllers\Manage\Workplace\ManageWorkplaceRelationshipController;
use App\Http\Controllers\Manage\Workplace\ManageWorkplaceTablePropController;
use App\Http\Controllers\Manage\Post\ManagePostPropController;
use App\Http\Controllers\Manage\Post\ManagePostRelationshipController;
use App\Http\Controllers\Manage\Post\ManagePostTablePropController;
use App\Http\Controllers\Manage\User\ManageUserPropController;
use App\Http\Controllers\Manage\User\ManageUserRelationshipController;
use App\Http\Controllers\Manage\User\ManageUserTablePropController;
use App\Http\Controllers\Render\Media\MediaActionRenderController;
use App\Http\Controllers\Render\Media\MediaEditController;
use App\Http\Controllers\Render\Media\MediaRenderController;
use App\Http\Controllers\Render\Post\PostActionRenderController;
use App\Http\Controllers\Render\Post\PostEditController;
use App\Http\Controllers\Render\Post\PostRenderController;
use App\Http\Controllers\Render\User\UserActionRenderController;
use App\Http\Controllers\Render\User\UserEditController;
use App\Http\Controllers\Render\User\UserRenderController;
use App\Http\Controllers\Render\Workplace\WorkplaceActionRenderController;
use App\Http\Controllers\Render\Workplace\WorkplaceRenderController;
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
        Route::resource('user/user_renderprop', UserRenderController::class);
        Route::resource('user/user_manage', UserActionRenderController::class);
        Route::resource('user/user_edit', UserEditController::class);
        Route::resource('media/media_renderprop', MediaRenderController::class);
        Route::resource('media/media_edit', MediaEditController::class);
        Route::resource('media/media_manage', MediaActionRenderController::class);
        Route::resource('media/media_edit', EditMediaActionRenderController::class);
        Route::resource('post/post_renderprop', PostRenderController::class);
        Route::resource('post/post_manage', PostActionRenderController::class);
        Route::resource('post/post_edit', EditMediaActionRenderController::class);
        Route::resource('workplace/workplace_renderprop', WorkplaceRenderController::class);
        Route::resource('workplace/workplace_manage', WorkplaceActionRenderController::class);
        Route::resource('workplace/workplace_edit', EditMediaActionRenderController::class);
        Route::resource('/upload/upload_add', UploadFileController::class);
        Route::get('/upload/{id}/download', [UploadFileController::class, 'download'])->name('upload_add.download');
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
        Route::resource('user/user_managerelationship', ManageUserRelationshipController::class);
        Route::resource('media/media_manageprop', ManageMediaPropController::class);
        Route::resource('media/media_managelineprop', ManageMediaTablePropController::class);
        Route::resource('media/media_managerelationship', ManageMediaRelationshipController::class);
        Route::resource('post/post_manageprop', ManagePostPropController::class);
        Route::resource('post/post_managelineprop', ManagePostTablePropController::class);
        Route::resource('post/post_managerelationship', ManagePostRelationshipController::class);
        Route::resource('workplace/workplace_manageprop', ManageWorkplacePropController::class);
        Route::resource('workplace/workplace_managelineprop', ManageWorkplaceTablePropController::class);
        Route::resource('workplace/workplace_managerelationship', ManageWorkplaceRelationshipController::class);

    });
});
Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

// Route::get('/mail-test', [MailController::class, 'index']);

// Route::post('/mail-test', [MailController::class, 'sendMail'])->name('send_mail');



// Route::get('/dashboard/user/manageprop', [ManageUserController::class, 'manageProp'])->name('user.manageProp');
// Route::post('/dashboard/user/manageprop', [ManageUserController::class, 'manageProp'])->name('user.manageProp');
// Route::get('/dashboard/user/managelineprop', [ManageLineController::class, 'manageLineProp'])->name('user.manageLineProp');
