<?php

use App\Http\Controllers\Admin\AdminSetPermissionController;
use App\Http\Controllers\Admin\AdminSetRoleController;
use App\Http\Controllers\Admin\AdminSetRoleSetController;
use App\Http\Controllers\Admin\Features\PermissionController;
use App\Http\Controllers\Admin\Features\RoleController;
use App\Http\Controllers\Admin\Features\RoleSetController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Manage\ManageStatusLibrary;
use App\Http\Controllers\Manage\Master\StatusDocType;
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
use App\Http\Controllers\Render\Media\MediaEditController;
use App\Http\Controllers\Render\Media\MediaRenderController;
use App\Http\Controllers\Render\Post\PostEditController;
use App\Http\Controllers\Render\Post\PostRenderController;
use App\Http\Controllers\Render\User\UserCreateController;
use App\Http\Controllers\Render\User\UserEditController;
use App\Http\Controllers\Render\User\UserRenderController;
use App\Http\Controllers\Render\Workplace\WorkplaceRenderController;
use App\Http\Controllers\TestController;
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

Route::group([
    'middleware' => ['auth', 'impersonate', 'role_set:guest|admin']
], function () {
    Route::group([
        'prefix' => 'dashboard'
    ], function () {
        Route::resource('user/user_renderprop', UserRenderController::class);
        Route::resource('user/user_addnew', UserCreateController::class);
        Route::resource('user/user_edit', UserEditController::class);
        Route::resource('media/media_renderprop', MediaRenderController::class);
        Route::resource('media/media_edit', MediaEditController::class);
        Route::resource('post/post_renderprop', PostRenderController::class);
        Route::resource('post/post_edit', PostEditController::class);
        Route::resource('workplace/workplace_renderprop', WorkplaceRenderController::class);
        Route::resource('workplace/workplace_edit', MediaEditController::class);
        Route::resource('/upload/upload_add', UploadFileController::class);
        Route::get('/upload/{id}/download', [UploadFileController::class, 'download'])->name('upload_add.download');
    });
    Route::group([
        'prefix' => 'propman'
    ], function () {
        Route::group([
            'prefix' => 'user',
            'middleware' => 'role:ADMIN-DATA-USERS'
        ], function () {
            Route::resource('user_manageprop', ManageUserPropController::class);
            Route::resource('user_managelineprop', ManageUserTablePropController::class);
            Route::resource('user_managerelationship', ManageUserRelationshipController::class);
        });
        Route::group([
            'prefix' => 'media',
            'middleware' => 'role:ADMIN-DATA-MEDIA'
        ], function () {
            Route::resource('media_manageprop', ManageMediaPropController::class);
            Route::resource('media_managelineprop', ManageMediaTablePropController::class);
            Route::resource('media_managerelationship', ManageMediaRelationshipController::class);
        });
        Route::group([
            'prefix' => 'post',
            'middleware' => 'role:ADMIN-DATA-POSTS'
        ], function () {
            Route::resource('post_manageprop', ManagePostPropController::class);
            Route::resource('post_managelineprop', ManagePostTablePropController::class);
            Route::resource('post_managerelationship', ManagePostRelationshipController::class);
        });
        Route::group([
            'prefix' => 'workplace',
            'middleware' => 'role:ADMIN-DATA-WORKPLACES'
        ], function () {
            Route::resource('workplace_manageprop', ManageWorkplacePropController::class);
            Route::resource('workplace_managelineprop', ManageWorkplaceTablePropController::class);
            Route::resource('workplace_managerelationship', ManageWorkplaceRelationshipController::class);
        });
    });
    Route::group([
        'prefix' => 'admin',
        'middleware' => ['role_set:admin']
    ], function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('role_sets', RoleSetController::class);
        Route::resource('setpermissions', AdminSetPermissionController::class);
        Route::post('setpermissions/syncpermission', [AdminSetPermissionController::class, 'store2'])->name('setpermissions.store2');
        Route::resource('setroles', AdminSetRoleController::class);
        Route::post('setroles/syncroles', [AdminSetRoleController::class, 'store2'])->name('setroles.store2');
        Route::resource('setrolesets', AdminSetRoleSetController::class);
        Route::post('setrolesets/syncrolesets', [AdminSetRoleSetController::class, 'store2'])->name('setrolesets.store2');
    });
});
Route::group([
    'middleware' => ['auth']
], function () {
    Route::get('/', [DashBoardController::class, 'index'])->name('dashboard');
    Route::get('impersonate/user/{id}', [AdminSetRoleSetController::class, 'impersonate'])->name('setrolesets.impersonate');
});
Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);


// Route::get('/mail-test', [MailController::class, 'index']);

// Route::post('/mail-test', [MailController::class, 'sendMail'])->name('send_mail');
Route::resource('test', TestController::class);

Route::resource('manage/statusLibrary', ManageStatusLibrary::class);
Route::resource('manage/statusDocType', StatusDocType::class);
