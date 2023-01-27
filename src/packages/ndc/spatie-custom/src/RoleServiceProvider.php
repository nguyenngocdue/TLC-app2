<?php

namespace Ndc\SpatieCustom;

use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Ndc\SpatieCustom\Contracts\RoleSet as RoleSetContract;
use Ndc\SpatieCustom\Contracts\Role as RoleContract;

class RoleServiceProvider extends ServiceProvider
{
    public function boot(RoleRegistrar $roleLoader)
    {
        $this->offerPublishing();

        $this->registerMacroHelpers();

        $this->registerModelBindings();

        if ($this->app->config['permission.register_role_check_method']) {
            $roleLoader->clearClassRoles();
            $roleLoader->registerRoles();
        }
        $this->app->singleton(RoleRegistrar::class, function ($app) use ($roleLoader) {
            return $roleLoader;
        });
    }

    public function register()
    {
        $this->callAfterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $this->registerBladeExtensions($bladeCompiler);
        });
    }
    protected function offerPublishing()
    {
        if (!function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }
    }
    protected function registerModelBindings()
    {
        $config = $this->app->config['permission.models'];

        if (!$config) {
            return;
        }

        $this->app->bind(RoleSetContract::class, $config['role_set']);
        $this->app->bind(RoleContract::class, $config['role']);
    }
    protected function registerBladeExtensions($bladeCompiler)
    {
        $bladeCompiler->directive('roleset', function ($arguments) {
            list($roleSet, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasRoleSet({$roleSet})): ?>";
        });
        $bladeCompiler->directive('elseroleset', function ($arguments) {
            list($roleSet, $guard) = explode(',', $arguments . ',');

            return "<?php elseif(auth({$guard})->check() && auth({$guard})->user()->hasRoleSet({$roleSet})): ?>";
        });
        $bladeCompiler->directive('endroleset', function () {
            return '<?php endif; ?>';
        });

        $bladeCompiler->directive('hasroleset', function ($arguments) {
            list($roleSet, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasRoleSet({$roleSet})): ?>";
        });
        $bladeCompiler->directive('endhasroleset', function () {
            return '<?php endif; ?>';
        });

        $bladeCompiler->directive('hasanyroleset', function ($arguments) {
            list($roles, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasAnyRole({$roles})): ?>";
        });
        $bladeCompiler->directive('endhasanyrole', function () {
            return '<?php endif; ?>';
        });

        $bladeCompiler->directive('hasallrolesets', function ($arguments) {
            list($roleSets, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasAllRoleSets({$roleSets})): ?>";
        });
        $bladeCompiler->directive('endhasallrolesets', function () {
            return '<?php endif; ?>';
        });

        $bladeCompiler->directive('unlessroleset', function ($arguments) {
            list($roleSet, $guard) = explode(',', $arguments . ',');

            return "<?php if(!auth({$guard})->check() || ! auth({$guard})->user()->hasRoleSet({$roleSet})): ?>";
        });
        $bladeCompiler->directive('endunlessroleset', function () {
            return '<?php endif; ?>';
        });

        $bladeCompiler->directive('hasexactrolessets', function ($arguments) {
            list($roleSets, $guard) = explode(',', $arguments . ',');

            return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasExactRoleSets({$roleSets})): ?>";
        });
        $bladeCompiler->directive('endhasexactrolesets', function () {
            return '<?php endif; ?>';
        });
    }
    protected function registerMacroHelpers()
    {
        if (!method_exists(Route::class, 'macro')) { // Lumen
            return;
        }
        Route::macro('role_set', function ($roleSets = []) {
            $roleSets = implode('|', Arr::wrap($roleSets));

            $this->middleware("role_set:$roleSets");

            return $this;
        });
    }
}
