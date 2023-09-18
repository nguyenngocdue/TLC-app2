<?php

namespace Ndc\SpatieCustom\Middlewares;

use App\Utils\Support\CurrentRoute;
use Brian2694\Toastr\Facades\Toastr;
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
                $message = 'User does not have the right permissions.';
                if (config('permission.display_permission_in_exception')) {
                    $message .= ' Necessary permissions are '.implode(', ', $permissions);
                }
                Toastr::success($message, 'Permission denied.');
                return redirect(route($type.'.show',$id));
            }
            throw UnauthorizedException::forPermissions($permissions);
        }
        return $next($request);
    }
}
