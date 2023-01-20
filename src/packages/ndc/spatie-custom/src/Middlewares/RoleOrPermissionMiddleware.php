<?php

namespace Ndc\SpatieCustom\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Ndc\SpatieCustom\Exceptions\UnauthorizedException;

class RoleOrPermissionMiddleware
{
    public function handle($request, Closure $next, $roleOrPermission, $guard = null)
    {
        $authGuard = Auth::guard($guard);
        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (!$authGuard->user()->roleSets[0]->hasAnyRole($rolesOrPermissions) && !$authGuard->user()->roleSets[0]->hasAnyPermission($rolesOrPermissions)) {
            throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
        }

        return $next($request);
    }
}
