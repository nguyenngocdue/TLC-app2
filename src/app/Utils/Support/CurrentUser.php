<?php

namespace App\Utils\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Ndc\SpatieCustom\Exceptions\UnauthorizedException;

class CurrentUser
{
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
            return $roles->map(fn ($item) => $item->name)->all();
        }
        return $roles;
    }
    public static function getPermissions($userAuth = null)
    {
        $permissions = static::getRoles($userAuth, false)
            ->map(
                fn ($role) => $role->permissions
                    ->map(fn ($item) => $item->name)->toArray()
            )->collapse()->all();
        return $permissions;
    }

    public static function get(): User
    {
        return Auth::user();
    }
    public static function id()
    {
        return self::get()->id;
    }
    public static function bookmark()
    {
        $settings = self::getSettings();
        $bookmarkSettings = $settings['bookmark_search'] ?? [];
        return $bookmarkSettings;
    }
}
