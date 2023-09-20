<?php

namespace Ndc\SpatieCustom\Traits;

use Illuminate\Support\Collection;
use Ndc\SpatieCustom\RoleRegistrar;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Traits\HasRoles as TraitsHasRoles;

trait HasRoles
{
    use TraitsHasRoles;
    use RefreshesRoleCache;

    public function giveRoleTo(...$roles)
    {
        $roles = collect($roles)
            ->flatten()
            ->reduce(function ($array, $role) {
                if (empty($role)) {
                    return $array;
                }

                $role = $this->getStoredRole($role);
                if (!$role instanceof Role) {
                    return $array;
                }

                $this->ensureModelSharesGuard($role);

                $array[$role->getKey()] = RoleRegistrar::$teams && !is_a($this, Role::class) ?
                    [RoleRegistrar::$teamsKey => getRolesTeamId()] : [];

                return $array;
            }, []);

        $model = $this->getModel();
        if ($model->exists) {
            $this->roles()->sync($roles, false);
            $model->load('roles');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($roles, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }
                    $model->roles()->sync($roles, false);
                    $model->load('roles');
                }
            );
        }
        if (is_a($this, get_class(app(RoleRegistrar::class)->getRoleSetClass()))) {
            //$this->forgetCachedRoleSets();
        }

        return $this;
    }
    public function getRoleClass()
    {
        if (! isset($this->roleClass)) {
            $this->roleClass = app(RoleRegistrar::class)->getRoleClass();
        }

        return $this->roleClass;
    }
    public function getRolesViaRoleSets(): Collection
    {
        return $this->loadMissing('roleSets', 'roleSets.roles')
            ->roleSets->flatMap(function ($roleSet) {
                return $roleSet->roles;
            })->sort()->values();
    }
}
