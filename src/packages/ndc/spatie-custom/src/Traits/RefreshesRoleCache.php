<?php

namespace Ndc\SpatieCustom\Traits;

use Ndc\SpatieCustom\RoleRegistrar;

trait RefreshesRoleCache
{

    public static function bootRefreshesRoleCache()
    {
        static::saved(function () {
            app(RoleRegistrar::class)->forgetCachedRoles();
        });

        static::deleted(function () {
            app(RoleRegistrar::class)->forgetCachedRoles();
        });
    }
}
