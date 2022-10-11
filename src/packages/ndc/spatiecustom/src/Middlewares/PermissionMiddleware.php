<?php

namespace Ndc\Spatiecustom\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Ndc\Spatiecustom\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);
        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);
        // dd($authGuard->user()->roleSets[0]->hasAnyPermission('create-zunit_test_1'));
        if (!$authGuard->user()->roleSets[0]->hasAnyPermission($permissions)) {
            throw UnauthorizedException::forPermissions($permissions);
        }
        return $next($request);
    }
}
