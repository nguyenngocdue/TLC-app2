<?php

namespace Ndc\Spatiecustom\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Ndc\Spatiecustom\Exceptions\UnauthorizedException;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        $roles = is_array($role)
            ? $role
            : explode('|', $role);
        if (!$authGuard->user()->roleSets[0]->hasAnyRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }
        return $next($request);
    }
}
