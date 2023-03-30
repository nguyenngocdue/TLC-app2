<?php

use App\Http\Controllers\AppMenuController;
use App\Http\Controllers\ComponentDemo\ComponentDemo;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Entities\EntityCRUDController;
use App\Http\Controllers\Entities\ManageJsonController;
use App\Http\Controllers\Entities\ViewAllController;
use App\Http\Controllers\Entities\ViewAllInvokerController;
use App\Http\Controllers\Notifications\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedisController;
use App\Http\Controllers\Reports\ReportIndexController;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Utils\ParserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\WelcomeDueController;
use App\Http\Controllers\WelcomeFortuneController;
use App\Http\Controllers\Workflow\ManageAppsController;
use App\Http\Controllers\Workflow\ManageStatusesController;
use App\Http\Controllers\Workflow\ManageWidgetsController;
use App\Utils\Support\Entities;
use App\Utils\Support\JsonControls;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

$entities = Entities::getAll();
$qrCodeApps = JsonControls::getQrCodeApps();
Auth::routes();
Route::group([
    'middleware' => ['auth', 'impersonate',]
], function ()  use ($entities) {
    Route::group([
        'prefix' => 'dashboard'
    ], function () use ($entities) {
        foreach ($entities as $entity) {
            $entityName = Str::getEntityName($entity);
            // $singular = Str::singular($entityName);
            // $ucfirstName = Str::ucfirst($singular);
            Route::resource("{$entityName}", ViewAllController::class)->only('index');
            Route::get("{$entityName}_ep", [ViewAllInvokerController::class, "exportCSV"])->name("{$entityName}_ep.exportCSV");
            Route::get("{$entityName}_qr", [ViewAllInvokerController::class, "showQRCode"])->name("{$entityName}_qr.showQRCode");
            Route::resource("{$entityName}", EntityCRUDController::class)->only('create', 'store', 'edit', 'update', 'show', 'destroy');
        }
        // dd();
        // Route::resource('/upload/upload_add', App\Http\Controllers\UploadFileController::class);
        // Route::get('/upload/{id}/download', [App\Http\Controllers\UploadFileController::class, 'download'])->name('upload_add.download');
    });

    Route::get('reports', [ReportIndexController::class, 'index'])->name('reportIndices.index');
    Route::get('notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::get('notifications/{type}/{id}/{idNotification}', [NotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::group([
        'prefix' => 'reports'
    ], function () use ($entities) {
        foreach ($entities as $entity) {
            $entityName = Str::getEntityName($entity);
            $singular = Str::singular($entityName);
            $ucfirstName = Str::ucfirst($singular);
            Route::group([
                'middleware' => "role:READ-WRITE-DATA-" . Str::upper($entityName) . "|ADMIN-DATA-" . Str::upper($entityName),
            ], function () use ($singular, $ucfirstName) {
                for ($i = 10; $i <= 50; $i += 10) {
                    $mode = str_pad($i, 3, '0', STR_PAD_LEFT);
                    $path = "App\\Http\\Controllers\\Reports\\Reports\\{$ucfirstName}_$mode";
                    $routeName = 'report-' . $singular . "_" . $mode;
                    $name = 'report-' . $singular . "/$mode";
                    if (class_exists($path)) Route::get($name, [$path, 'index'])->name($routeName);

                    $path = "App\\Http\\Controllers\\Reports\\Registers\\{$ucfirstName}_$mode";
                    $routeName = 'register-' . $singular . "_" . $mode;
                    $name = 'register-' . $singular . "/$mode";
                    if (class_exists($path)) Route::get($name, [$path, 'index'])->name($routeName);

                    $path = "App\\Http\\Controllers\\Reports\\Documents\\{$ucfirstName}_$mode";
                    $routeName = 'document-' . $singular . "_" . $mode;
                    $name = 'document-' . $singular . "/$mode";
                    if (class_exists($path)) Route::get($name, [$path, 'index'])->name($routeName);
                }
            });
        }
    });
    Route::group([
        'prefix' => 'config'
    ], function () use ($entities) {
        foreach ($entities as $entity) {
            $entityName = Str::getEntityName($entity);
            $singular = Str::singular($entityName);
            Route::group([
                'middleware' => "role:ADMIN-DATA-" . Str::upper($entityName),
            ], function () use ($singular) {

                Route::resource("{$singular}_ppt", ManageJsonController::class)->only('index', 'store', 'create');

                Route::resource("{$singular}_prp", ManageJsonController::class)->only('index', 'store', 'create');
                Route::resource("{$singular}_dfv", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rls", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_ltn", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_stt", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_tst", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_wdw", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_atb", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_dfn", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_itm", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_bic", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rtm", ManageJsonController::class)->only('index', 'store');

                Route::resource("{$singular}_vsb", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rol", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rqr", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_hdn", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_vsb-wl", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rol-wl", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_rqr-wl", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_hdn-wl", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_cpb", ManageJsonController::class)->only('index', 'store');
                Route::resource("{$singular}_unt", ManageJsonController::class)->only('index', 'store', 'create');
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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboards.index');
    Route::get('me', [ProfileController::class, 'profile'])->name('me.index');
    Route::put('updateUserSettings', UpdateUserSettings::class)->name('updateUserSettings');
    Route::get('impersonate/user/{id}', [App\Http\Controllers\Admin\AdminSetRoleSetController::class, 'impersonate'])->name('setrolesets.impersonate');
});


Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
// Route::get('/mail-test', [MailController::class, 'index']);
// Route::post('/mail-test', [MailController::class, 'sendMail'])->name('send_mail');
// Route::get('test', [HomeController::class, 'index']);

Route::resource('welcome', WelcomeController::class)->only('index');
Route::resource('welcome-due', WelcomeDueController::class)->only('index');
Route::resource('welcome-fortune', WelcomeFortuneController::class)->only('index', 'store');

Route::resource('utils/parser', ParserController::class)->only('index', 'store');

Route::get('app-menu', [AppMenuController::class, 'index']);
Route::group([
    'prefix' => 'dashboard/workflow',
], function () {
    Route::resource('manageStatuses', ManageStatusesController::class)->only('index', 'store', 'create');
    Route::resource('manageWidgets', ManageWidgetsController::class)->only('index', 'store', 'create');
    Route::resource('manageApps', ManageAppsController::class)->only('index', 'store', 'create');
});
Route::get('components', [ComponentDemo::class, 'index']);
Route::get('redis', [RedisController::class, 'index']);
Route::group([
    'prefix' => 'app'
], function () use ($qrCodeApps) {
    foreach ($qrCodeApps as $qrCodeApp) {
        Route::get("{$qrCodeApp}/{slug}", [EntityCRUDController::class, "showQRApp"])->name("{$qrCodeApp}.showQRApp");
    }
});

//This is for back compatible with TLC App 1, QR Code scannings
Route::get('/modular/{slug}',  fn ($slug) => redirect('app/pj_modules/' . $slug));
Route::get('/unit/{slug}', fn ($slug) => redirect('app/pj_units/' . $slug));
Route::get('/shipment/{slug}', fn ($slug) => redirect('app/pj_shipments/' . $slug));
