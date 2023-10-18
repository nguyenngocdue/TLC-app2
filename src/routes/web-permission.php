<?php

use App\Utils\Support\Entities;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LdapRecord\Laravel\LdapUserRepository;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;



Route::group([
    'middleware' => ['auth', 'impersonate',]
], function () {
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
        Route::get('permissions_matrix', [App\Http\Controllers\Admin\AdminPermissionMatrixController::class, 'index'])->name('permissions_matrix');

        // Route::resource('permissions2', App\Http\Controllers\Permission\Permission::class);

        Route::get('ldap-check-email',function(Request $request){
            $credentials = [
                'userprincipalname' => $request->email,
                'password' => '123456789',
                'fallback' => [
                    'email' => $request->email,
                    'password' => '123456789',
                ],
            ];
            $query = (new LdapUserRepository('LdapRecord\Models\ActiveDirectory\User'))->query();
            foreach ($credentials as $key => $value) {
                if (Str::contains($key, ['password', 'fallback'])) {
                    continue;
                }
                if (is_array($value) || $value instanceof Arrayable) {
                    $query->whereIn($key, $value);
                } else {
                    // check faild password
                    $query->where($key, $value);
                }
            }
            if (is_null($query->first())) {
                return response()->json('Email Address not found');
            }
            return response()->json('Email Address:'.$request->email);
        });
    });
});
