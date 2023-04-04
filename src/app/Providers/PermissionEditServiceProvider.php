<?php

namespace App\Providers;

use App\Providers\Support\TraitSupportPermissionGate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class PermissionEditServiceProvider extends ServiceProvider
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
        Gate::define('edit', function ($user, $model) {
            return $this->editAndDelete($user, $model);
        });
        Gate::define('edit-others', function ($user, $model) {
            return $this->editAndDeleteOther($user, $model);
        });
        Gate::before(function ($user, $ability) {
            return $user->hasRoleSet('super-admin') ? true : null;
        });
    }
}
