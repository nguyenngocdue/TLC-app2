<?php

namespace Ndc\Spatiecustom;

use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Ndc\Spatiecustom\Contracts\RoleSet as RoleSetContract;
use Ndc\Spatiecustom\Contracts\Role as RoleContract;
use Spatie\Permission\PermissionServiceProvider as PermissionPermissionServiceProvider;

class PermissionServiceProvider extends PermissionPermissionServiceProvider
{
    protected function registerBladeExtensions($bladeCompiler)
    {
        $bladeCompiler->directive('role', function ($arguments) {
            list($role, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->roleSets[0]->hasRole({$role})): ?>";
        });
        $bladeCompiler->directive('elserole', function ($arguments) {
            list($role, $guard) = explode(',', $arguments . ',');

            return "<?php elseif(auth({$guard})->check() && auth({$guard})->user()->roleSets[0]->hasRole({$role})): ?>";
        });
        $bladeCompiler->directive('endrole', function () {
            return '<?php endif; ?>';
        });

        $bladeCompiler->directive('hasrole', function ($arguments) {
            list($role, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->roleSets[0]->hasRole({$role})): ?>";
        });
        $bladeCompiler->directive('endhasrole', function () {
            return '<?php endif; ?>';
        });

        $bladeCompiler->directive('hasanyrole', function ($arguments) {
            list($roles, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->roleSets[0]->hasAnyRole({$roles})): ?>";
        });
        $bladeCompiler->directive('endhasanyrole', function () {
            return '<?php endif; ?>';
        });

        $bladeCompiler->directive('hasallroles', function ($arguments) {
            list($roles, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->roleSets[0]->hasAllRoles({$roles})): ?>";
        });
        $bladeCompiler->directive('endhasallroles', function () {
            return '<?php endif; ?>';
        });

        $bladeCompiler->directive('unlessrole', function ($arguments) {
            list($role, $guard) = explode(',', $arguments . ',');

            return "<?php if(!auth({$guard})->check() || ! auth({$guard})->user()->roleSets[0]->hasRole({$role})): ?>";
        });
        $bladeCompiler->directive('endunlessrole', function () {
            return '<?php endif; ?>';
        });

        $bladeCompiler->directive('hasexactroles', function ($arguments) {
            list($roles, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->roleSets[0]->hasExactRoles({$roles})): ?>";
        });
        $bladeCompiler->directive('endhasexactroles', function () {
            return '<?php endif; ?>';
        });
    }
}
