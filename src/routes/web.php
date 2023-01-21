<?php

use App\Http\Controllers\AppMenuController;
use App\Http\Controllers\ComponentDemo\ComponentDemo;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Workflow\ManageAppsController;
use App\Http\Controllers\Workflow\ManageStatusesController;
use App\Http\Controllers\Workflow\ManageWidgetsController;
use App\Utils\Support\Entities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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
            $singular = Str::singular($entityName);
            $ucfirstName = Str::ucfirst($singular);
            $path = "App\\Http\\Controllers\\Entities\\{$ucfirstName}\\";
            Route::resource("{$entityName}", "{$path}ViewAllController")->only('index');
            Route::resource("{$entityName}", "{$path}EntityCRUDController")->only('create', 'store', 'edit', 'update', 'show');
        }
        // Route::resource('/upload/upload_add', App\Http\Controllers\UploadFileController::class);
        // Route::get('/upload/{id}/download', [App\Http\Controllers\UploadFileController::class, 'download'])->name('upload_add.download');
    });
    Route::group([
        'prefix' => 'config'
    ], function () use ($entities) {
        foreach ($entities as $entity) {
            $entityName = $entity->getTable();
            $singular = Str::singular($entityName);
            $ucfirstName = Str::ucfirst($singular);
            Route::group([
                'middleware' => "role:ADMIN-DATA-" . Str::upper($entityName),
            ], function () use ($singular, $ucfirstName) {
                $path = "App\\Http\\Controllers\\Entities\\{$ucfirstName}\\";

                Route::resource("{$singular}_prp", "{$path}ManageJsonController")->only('index', 'store', 'create');
                Route::resource("{$singular}_dfv", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_rls", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_ltn", "{$path}ManageJsonController")->only('index', 'store', 'create');
                Route::resource("{$singular}_stt", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_tst", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_atb", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_stn", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_bic", "{$path}ManageJsonController")->only('index', 'store');

                Route::resource("{$singular}_vsb", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_rol", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_rqr", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_hdn", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_rol-exc", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_hdn-exc", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_cpb", "{$path}ManageJsonController")->only('index', 'store');
                Route::resource("{$singular}_unt", "{$path}ManageJsonController")->only('index', 'store', 'create');
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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::put('updateUserSettings', UpdateUserSettings::class)->name('updateUserSettings');
    Route::get('impersonate/user/{id}', [App\Http\Controllers\Admin\AdminSetRoleSetController::class, 'impersonate'])->name('setrolesets.impersonate');
});

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
// Route::get('/mail-test', [MailController::class, 'index']);
// Route::post('/mail-test', [MailController::class, 'sendMail'])->name('send_mail');
Route::get('test', [HomeController::class, 'index']);
Route::get('welcome', [WelcomeController::class, 'index']);
Route::get('app-menu', [AppMenuController::class, 'index']);
Route::group([
    'prefix' => 'dashboard/workflow',
], function () {
    Route::resource('manageStatuses', ManageStatusesController::class)->only('index', 'store', 'create');
    Route::resource('manageWidgets', ManageWidgetsController::class)->only('index', 'store', 'create');
    Route::resource('manageApps', ManageAppsController::class)->only('index', 'store', 'create');
});
Route::get('components', [ComponentDemo::class, 'index']);
