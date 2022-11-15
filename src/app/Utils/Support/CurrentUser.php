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

    public static function isAdmin()
    {
        return self::isRoleSet('admin');
    }

    public static function isRoleSet($roleSet)
    {
        $userAuth = Auth::user();
        if (!$userAuth) {
            throw UnauthorizedException::notLoggedIn();
        }
        $roleSets = is_array($roleSet)
            ? $roleSet
            : explode('|', $roleSet);
        if (!$userAuth->hasAnyRoleSet($roleSets)) {
            return false;
        }
        return true;
    }
}
