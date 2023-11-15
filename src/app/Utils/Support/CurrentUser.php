<?php

namespace App\Utils\Support;

use App\Models\User;
use App\Utils\System\GetSetCookie;
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
    public static function getTokenForApi()
    {
        $token = '';
        if (GetSetCookie::hasCookie('tlc_token')) {
            $token = GetSetCookie::getCookie('tlc_token');
        }
        return $token;
    }
    public static function isLoggedIn()
    {
        return !!Auth::user();
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
    public static function hasPermissionTo($permission)
    {
        $userAuth = static::checkAuth();
        if (isset($userAuth->roleSets[0])) {
            return $userAuth->roleSets[0]->hasPermissionTo($permission);
        }
        return false;
    }

    public static function isAdmin()
    {
        return self::isRoleSet('admin') || self::isRoleSet('super-admin');
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
    private static function getPermissionsCache($userAuth = null)
    {
        $permissions = static::getRoles($userAuth, false);
        $ids = join(',', $permissions->pluck('id')->toArray());
        $sql = [];
        if ($ids) { //In case if no permissions found (workers, guests, ...)
            $sql = DB::select(
                "SELECT  permissions.name
                FROM permissions INNER JOIN role_has_permissions 
                ON permissions.id = role_has_permissions.permission_id 
                WHERE role_has_permissions.role_id IN ($ids)"
            );
        }
        $permissions = [];
        foreach ($sql as $value) {
            $permissions[] = $value->name;
        }
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
    public static function getWorkPlace()
    {
        $user = self::get();
        return $user->getWorkPlace;
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

    public static function isExternalInspector()
    {
        return in_array(CurrentUser::get()->discipline, [138]); //138: External Inspector
    }

    public static function isClient()
    {
        return in_array(CurrentUser::get()->discipline, []); //: Client
    }

    public static function getViewSuffix()
    {
        switch (true) {
            case static::isClient():
                return "-client";
            case static::isExternalInspector():
                return "-external-inspector";
            default:
                return "";
        }
    }
}
