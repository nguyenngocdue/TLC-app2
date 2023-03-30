<?php

namespace App\Providers;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class PermissionEditServiceProvider extends ServiceProvider
{
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
            if (!CurrentUser::isAdmin()) {
                $isTree = $this->useTree($model);
                if ($isTree) {
                    return true;
                }
                return $user->id == $model->owner_id;
            }
            return true;
        });
        Gate::define('edit-others', function ($user, $model) {
            if (!CurrentUser::isAdmin()) {
                $isTree = $this->useTree($model);
                if ($user->id == $model->owner_id) {
                    return true;
                }
                if (!$isTree) {
                    return $user->id == $model->owner_id;
                }
                foreach ($this->treeCompany($user) as $value) {
                    if ($model->owner_id == $value->id) {
                        return true;
                    }
                }
                return false;
            }
            return true;
        });
        Gate::before(function ($user, $ability) {
            return $user->hasRoleSet('super-admin') ? true : null;
        });
    }
    private function useTree($model)
    {
        $type = Str::singular($model->getTable());
        return LibApps::getFor($type)['apply_approval_tree'] ?? false;
    }
    private function treeCompany($user)
    {
        return BuildTree::getTreeByOptions($user->id, $user->viewport_uids, $user->leaf_uids, false, true);
    }
}
