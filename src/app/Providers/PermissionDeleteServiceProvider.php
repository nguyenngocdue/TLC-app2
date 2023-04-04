<?php

namespace App\Providers;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\User;
use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class PermissionDeleteServiceProvider extends ServiceProvider
{
    use TraitSupportPermissionGate;
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('delete', function ($user, $model) {
            return $this->editAndDelete($user, $model);
        });
        Gate::define('delete-others', function ($user, $model) {
            return $this->editAndDeleteOther($user, $model);
        });
    }
}
