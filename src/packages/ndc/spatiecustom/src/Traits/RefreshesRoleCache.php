<?php

namespace Ndc\Spatiecustom\Traits;

use Ndc\Spatiecustom\RoleRegistrar;

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
