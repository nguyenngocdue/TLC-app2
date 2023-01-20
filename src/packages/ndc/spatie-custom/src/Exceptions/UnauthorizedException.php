<?php

namespace Ndc\SpatieCustom\Exceptions;

use Spatie\Permission\Exceptions\UnauthorizedException as ExceptionsUnauthorizedException;

class UnauthorizedException extends ExceptionsUnauthorizedException
{
    public static function forRoleSets(array $roleSets): self
    {
        $message = 'User does not have the right Role Sets.';

        if (config('permission.display_permission_in_exception')) {
            $permStr = implode(', ', $roleSets);
            $message = 'User does not have the right Role Set. Necessary roles are ' . $permStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredRoles = $roleSets;

        return $exception;
    }
}
