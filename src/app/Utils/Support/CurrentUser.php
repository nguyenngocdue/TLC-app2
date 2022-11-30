<?php

namespace App\Utils\Support;

use Illuminate\Support\Facades\Auth;
use Ndc\Spatiecustom\Exceptions\UnauthorizedException;

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
    public static function getRoleSet()
    {
        $userAuth = static::checkAuth();
        $roleSets = $userAuth->roleSets[0]?->name;
        return $roleSets;
    }
    public static function getRoles($getCollectionRole = true)
    {
        $userAuth = static::checkAuth();
        $roles = $userAuth->getRolesViaRoleSets();
        if ($getCollectionRole) {
            return $roles->map(fn ($item) => $item->name)->all();
        }
        return $roles;
    }
    public static function getPermissions()
    {
        $permissions = static::getRoles(false)
            ->map(
                fn ($role) => $role->permissions
                    ->map(fn ($item) => $item->name)->toArray()
            )->collapse()->all();
        return $permissions;
    }
}
