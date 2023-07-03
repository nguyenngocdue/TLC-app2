<?php

use App\Http\Controllers\Admin\AdminSetRoleSetController;
use App\Http\Controllers\Api\v1\System\NotificationsController as SystemNotificationsController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Notifications\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\UpdateUserSettingsApi;
use App\Http\Controllers\Utils\MyCompanyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Route::group([
    'middleware' => ['auth']
], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('me', [ProfileController::class, 'profile'])->name('me.index');
    Route::get('profile/{id}', [ProfileController::class, 'profile'])->name('me.index');
    Route::put('updateUserSettings', UpdateUserSettings::class)->name('updateUserSettings');
    Route::put('updateBookmark', [BookmarkController::class, 'updateBookmark'])->name('updateBookmark');
    Route::put('updateUserSettingsApi', [UpdateUserSettingsApi::class, 'updateUserSettingsApi'])->name('updateUserSettingsApi');
    Route::get('impersonate/user/{id}', [AdminSetRoleSetController::class, 'impersonate'])->name('setrolesets.impersonate');
    // Route::get('app-menu', [AppMenuController::class, 'index']);
    Route::get('my-company', [MyCompanyController::class, 'index'])->name("my-company.index");
    Route::get('reset', fn () => (new UpdateUserSettings())(new Request(['action' => 'resetAllSettings']), '/'));
    // Route::get('gitpull202', fn () => Log::info("WebHook Called"));
    Route::get('notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::get('notifications/{type}/{id}/{idNotification}', [NotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::get('system/notifications', [SystemNotificationsController::class, 'notifications']);
    Route::get('system/notificationsRender', [SystemNotificationsController::class, 'notificationsRender']);
});
