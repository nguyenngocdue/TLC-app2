<?php

namespace Ndc\Spatiecustom\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Ndc\Spatiecustom\Exceptions\UnauthorizedException;

class RoleSetMiddleware
{
    public function handle($request, Closure $next, $roleSet, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roleSets = is_array($roleSet)
            ? $roleSet
            : explode('|', $roleSet);
        if (!$authGuard->user()->hasAnyRoleSet($roleSets)) {
            throw UnauthorizedException::forRoleSets($roleSets);
        }

        return $next($request);
    }
}
