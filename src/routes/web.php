<?php

use App\Http\Controllers\ComponentLib;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Manage\ManageStatusLibrary;
use App\Http\Controllers\Manage\Master\StatusDocType;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Workflow\ManageStatuses;
use App\Utils\Support\Entities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

$entities = Entities::getAll();
Auth::routes();
Route::group([
    'middleware' => ['auth', 'impersonate', 'role_set:guest|admin']
], function ()  use ($entities) {
    Route::group([
        'prefix' => 'dashboard'
    ], function () use ($entities) {
        foreach ($entities as $entity) {
            $entityName = $entity->getTable();
            $ucfirstName = Str::ucfirst($entityName);
            Route::resource("$entityName/{$entityName}_viewall", "App\Http\Controllers\Render\\{$ucfirstName}\\{$ucfirstName}ViewAllController");
            Route::resource("$entityName/{$entityName}_addnew", "App\Http\Controllers\Render\\{$ucfirstName}\\{$ucfirstName}CreateController");
            Route::resource("$entityName/{$entityName}_edit", "App\Http\Controllers\Render\\{$ucfirstName}\\{$ucfirstName}EditController");
        }
        Route::resource('/upload/upload_add', App\Http\Controllers\UploadFileController::class);
        Route::get('/upload/{id}/download', [App\Http\Controllers\UploadFileController::class, 'download'])->name('upload_add.download');
    });
    Route::group([
        'prefix' => 'propman'
    ], function () use ($entities) {
        foreach ($entities as $entity) {
            $entityName = $entity->getTable();
            $singular = Str::singular($entityName);
            $ucfirstName = Str::ucfirst($singular);
            $upperCaseName = Str::upper($entityName);
            Route::group([
                'prefix' => $singular,
                'middleware' => "role:ADMIN-DATA-$upperCaseName"
            ], function () use ($singular, $ucfirstName) {
                Route::resource("{$singular}_mngprop", "App\Http\Controllers\Manage\\{$ucfirstName}\\PropController");
                // Route::resource("{$singular}_mnglnprop", "App\Http\Controllers\Manage\\{$ucfirstName}\\TablePropController");
                Route::resource("{$singular}_mngrls", "App\Http\Controllers\Manage\\{$ucfirstName}\\RelationshipController");
                Route::resource("{$singular}_mngstt", "App\Http\Controllers\Manage\\{$ucfirstName}\\StatusController");
            });
        }
    });
    Route::group([
        'prefix' => 'dashboard/admin',
        'middleware' => ['role_set:admin']
    ], function () {
        Route::resource('roles', App\Http\Controllers\Admin\Features\RoleController::class);
        Route::resource('permissions', App\Http\Controllers\Admin\Features\PermissionController::class);
        Route::resource('role_sets', App\Http\Controllers\Admin\Features\RoleSetController::class);
        Route::resource('setpermissions', App\Http\Controllers\Admin\AdminSetPermissionController::class);
        Route::post('setpermissions/syncpermission', [App\Http\Controllers\Admin\AdminSetPermissionController::class, 'store2'])->name('setpermissions.store2');
        Route::resource('setroles', App\Http\Controllers\Admin\AdminSetRoleController::class);
        Route::post('setroles/syncroles', [App\Http\Controllers\Admin\AdminSetRoleController::class, 'store2'])->name('setroles.store2');
        Route::resource('setrolesets', App\Http\Controllers\Admin\AdminSetRoleSetController::class);
        Route::post('setrolesets/syncrolesets', [App\Http\Controllers\Admin\AdminSetRoleSetController::class, 'store2'])->name('setrolesets.store2');

        Route::resource('permissions2', App\Http\Controllers\Permission\Permission::class);
    });
});
Route::group([
    'middleware' => ['auth']
], function () {
    Route::get('/', [DashBoardController::class, 'index'])->name('dashboard');
    Route::put('/{id}', [SettingController::class, 'update'])->name('settingUpdate');
    Route::get('impersonate/user/{id}', [App\Http\Controllers\Admin\AdminSetRoleSetController::class, 'impersonate'])->name('setrolesets.impersonate');
});
Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
// Route::get('/mail-test', [MailController::class, 'index']);
// Route::post('/mail-test', [MailController::class, 'sendMail'])->name('send_mail');
Route::resource('test', HomeController::class);
// Route::resource('manage/manage_statusLibrary', ManageStatusLibrary::class);
// Route::resource('manage/status', ManageStatusDoc::class);
// Route::resource('statuses/statusLibrary', ManageStatusLibrary::class);

Route::resource('dashboard/workflow/statuses', ManageStatuses::class)->only('index', 'store', 'create');

Route::resource('manage/statusDocType', StatusDocType::class);
Route::resource('/abc', HomeController::class);

Route::get('components', [ComponentLib::class, 'index']);

// Route::get('/test', function () {
//     $user = App\Models\User::first();
//     $user->notify(new CreatePostSuccess);
//     return 'Done';
// });
