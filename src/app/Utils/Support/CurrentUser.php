<?php

namespace App\Utils\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ndc\SpatieCustom\Exceptions\UnauthorizedException;

class CurrentUser
{
    static $singleton = null;
    static function singletonCache($userAuth = null)
    {
        if (is_null(static::$singleton)) {
            static::$singleton = static::getPermissionsCache($userAuth);
        }
        return static::$singleton;
    }
    public static function getSettings()
    {
        return Auth::user()->settings;
    }
    private static function checkAuth()
    {
        $userAuth = Auth::user();
        if (!$userAuth) {
            throw UnauthorizedException::notLoggedIn();
        }
        return $userAuth;
    }
    private static function checkLoginUser($userAuth)
    {
        if (!$userAuth) {
            $userAuth = static::checkAuth();
        }
        return $userAuth;
    }

    public static function isAdmin()
    {
        return self::isRoleSet('admin');
    }

    public static function isRoleSet($roleSet)
    {
        $userAuth = static::checkAuth();
        $roleSets = is_array($roleSet)
            ? $roleSet
            : explode('|', $roleSet);
        if (!$userAuth->hasAnyRoleSet($roleSets)) {
            return false;
        }
        return true;
    }
    public static function getRoleSet($userAuth = null)
    {
        $userAuth = static::checkLoginUser($userAuth);
        $roleSets = $userAuth->roleSets[0]->name ?? null;
        return $roleSets;
    }
    public static function getRoles($userAuth = null, $getCollectionRole = true)
    {
        $userAuth = static::checkLoginUser($userAuth);
        $roles = $userAuth->getRolesViaRoleSets();
        if ($getCollectionRole) {
            return $roles->pluck('name');
        }
        return $roles;
    }
    public static function getPermissionsCache($userAuth = null)
    {
        $permissions = static::getRoles($userAuth, false);
        $ids = join(',', $permissions->pluck('id')->toArray());
        $permissions = DB::select(
            "SELECT role_has_permissions.role_id AS pivot_role_id, 
            role_has_permissions.permission_id AS pivot_permission_id  
            FROM permissions INNER JOIN role_has_permissions 
            ON permissions.id = role_has_permissions.permission_id 
            WHERE role_has_permissions.role_id IN ($ids)"
        );
        return $permissions;
    }
    public static function getPermissions($userAuth = null)
    {
        return static::singletonCache($userAuth);
    }

    public static function get(): ?User
    {
        return Auth::user();
    }
    public static function id()
    {
        $cu = self::get();
        return $cu ? $cu->id : null;
    }
    public static function bookmark()
    {
        $settings = self::getSettings();
        $bookmarkSettings = $settings['bookmark_search'] ?? [];
        return $bookmarkSettings;
    }
}
