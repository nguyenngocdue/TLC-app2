<?php

use App\Events\Test;
use App\Http\Controllers\Admin\AdminSetRoleSetController;
use App\Http\Controllers\Api\v1\Social\PostController;
use App\Http\Controllers\Api\v1\System\NotificationsController as SystemNotificationsController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyOrgChartController;
use App\Http\Controllers\Notifications\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSocialController;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\UpdateUserSettingsApi;
use App\Http\Controllers\Utils\MyCompanyController;
use App\Http\Controllers\Utils\OrphanManyToManyController;
use App\Http\Controllers\Utils\ThumbnailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Route::group([
    'middleware' => ['auth']
], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('posts', PostController::class)->only(['store','update','destroy']);
    Route::get('me', [ProfileController::class, 'profile'])->name('me.index');
    Route::get('my-org-chart', [MyOrgChartController::class, 'index'])->name('myOrgChart.index');
    Route::get('profile/{id}', [ProfileController::class, 'profile'])->name('profile.index');
    Route::get('profile_social/{id}', [ProfileSocialController::class, 'profileSocial'])->name('profileSocial.index');
    Route::put('updateUserSettings', UpdateUserSettings::class)->name('updateUserSettings');
    Route::put('updateBookmark', [BookmarkController::class, 'updateBookmark'])->name('updateBookmark');
    Route::put('updateUserSettingsApi', [UpdateUserSettingsApi::class, 'updateUserSettingsApi'])->name('updateUserSettingsApi');
    Route::put('updateUserSettingsFilterApi', [UpdateUserSettingsApi::class, 'updateUserSettingsFilterApi'])->name('updateUserSettingsFilterApi');
    Route::get('impersonate/user/{id}', [AdminSetRoleSetController::class, 'impersonate'])->name('setrolesets.impersonate');
    // Route::get('app-menu', [AppMenuController::class, 'index']);
    Route::get('my-company', [MyCompanyController::class, 'index'])->name("my-company.index");
    Route::get('reset', fn () => (new UpdateUserSettings())(new Request(['action' => 'resetAllSettings']), '/'));
    // Route::get('gitpull202', fn () => Log::info("WebHook Called"));
    Route::get('notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::get('notifications/{type}/{id}/{idNotification}', [NotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::get('system/notifications', [SystemNotificationsController::class, 'notifications']);
    Route::get('system/notificationsRender', [SystemNotificationsController::class, 'notificationsRender']);
    Route::get('utils/createThumbnail', [ThumbnailController::class, 'index'])->name('createThumbnail.index');
    Route::post('utils/createThumbnail', [ThumbnailController::class, 'create'])->name('createThumbnail.create');
    Route::get('orphan/many_to_many', [OrphanManyToManyController::class, 'get'])->name('orphan.index');
    Route::post('orphan/many_to_many', [OrphanManyToManyController::class, 'destroy'])->name('orphan.destroy');
    // Route::get('test', function () {
    //     event(new Test(['DinhCanh', '30031997']));
    // });
});
