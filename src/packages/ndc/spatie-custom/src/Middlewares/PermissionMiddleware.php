<?php

namespace Ndc\SpatieCustom\Middlewares;

use App\Utils\Support\CurrentRoute;
use Closure;
use Ndc\SpatieCustom\Exceptions\UnauthorizedException;

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
        if (!$authGuard->user()->roleSets[0]->hasAnyPermission($permissions)) {
            $type = CurrentRoute::getTypePlural();
            $id = CurrentRoute::getEntityId($type);
            $action = CurrentRoute::getControllerAction();
            if($action == 'edit'){
                return redirect(route($type.'.show',$id));
            }
            throw UnauthorizedException::forPermissions($permissions);
        }
        return $next($request);
    }
}
