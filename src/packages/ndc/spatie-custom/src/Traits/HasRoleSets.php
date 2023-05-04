<?php

namespace Ndc\SpatieCustom\Traits;

use Ndc\SpatieCustom\Contracts\Role as ContractsRole;
use Ndc\SpatieCustom\Contracts\RoleSet;
use Ndc\SpatieCustom\RoleRegistrar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Role;

trait HasRoleSets
{
    use HasRoles;

    private $roleSetClass;
    public static function bootHasRoleSets()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                return;
            }

            $model->roleSets()->detach();
        });
    }
    public function getRoleSetClass()
    {
        if (!isset($this->roleSetClass)) {
            $this->roleSetClass = app(RoleRegistrar::class)->getRoleSetClass();
        }
        return $this->roleSetClass;
    }

    /**
     * A model may have multiple roles.
     */
    public function roleSets(): BelongsToMany
    {
        $relation = $this->morphToMany(
            config('permission.models.role_set'),
            'model',
            config('permission.table_names.model_has_role_sets'),
            config('permission.column_names.model_morph_key'),
            RoleRegistrar::$pivotRoleSet
        );
        if (!RoleRegistrar::$teams) {
            return $relation;
        }
        return $relation->wherePivot(RoleRegistrar::$teamsKey, getRolesTeamId())
            ->where(function ($q) {
                $teamField = config('permission.table_names.role_sets') . '.' . RoleRegistrar::$teamsKey;
                $q->whereNull($teamField)->orWhere($teamField, getRolesTeamId());
            });
    }


    public function scopeRoleSet(Builder $query, $roleSets, $guard = null): Builder
    {
        if ($roleSets instanceof Collection) {
            $roleSets = $roleSets->all();
        }

        $roleSets = array_map(function ($roleSet) use ($guard) {
            if ($roleSet instanceof RoleSet) {
                return $roleSet;
            }

            $method = is_numeric($roleSet) ? 'findById' : 'findByName';

            return $this->getRoleSetClass()->{$method}($roleSet, $guard ?: $this->getDefaultGuardName());
        }, Arr::wrap($roleSets));

        return $query->whereHas('role_sets', function (Builder $subQuery) use ($roleSets) {
            $roleSetClass = $this->getRoleSetClass();
            $key = (new $roleSetClass())->getKeyName();
            $subQuery->whereIn(config('permission.table_names.role_sets') . ".$key", \array_column($roleSets, $key));
        });
    }


    public function assignRoleSet(...$roleSets)
    {
        $roleSets = collect($roleSets)
            ->flatten()
            ->reduce(function ($array, $roleSet) {
                if (empty($roleSet)) {
                    return $array;
                }

                $roleSet = $this->getStoredRoleSet($roleSet);
                if (!$roleSet instanceof RoleSet) {
                    return $array;
                }
                $this->ensureModelSharesGuard($roleSet);
                $array[$roleSet->getKey()] = RoleRegistrar::$teams && !is_a($this, ContractsRole::class) ?
                    [RoleRegistrar::$teamsKey => getRolesTeamId()] : [];

                return $array;
            }, []);
        $model = $this->getModel();
        if ($model->exists) {
            $this->roleSets()->sync($roleSets, false);
            $model->load('roleSets');
            // error_log($model->load('roleSets'));
        } else {

            $class = \get_class($model);
            $class::saved(
                function ($object) use ($roleSets, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }
                    $model->roleSets()->sync($roleSets, false);
                    $model->load('roleSets');
                }
            );
        }
        if (is_a($this, get_class($this->getRoleClass()))) {
            $this->forgetCachedRoles();
        }

        return $this;
    }


    public function removeRoleSet($roleSet)
    {
        $this->roleSets()->detach($this->getStoredRoleSet($roleSet));

        $this->load('roleSets');

        if (is_a($this, get_class($this->getRoleClass()))) {
            $this->forgetCachedRoles();
        }

        return $this;
    }


    public function syncRoleSets(...$roleSets)
    {
        $this->roleSets()->detach();
        return $this->assignRoleSet($roleSets);
    }


    public function hasRoleSet($roleSets, string $guard = null): bool
    {
        if (is_string($roleSets) && false !== strpos($roleSets, '|')) {
            $roleSets = $this->convertPipeToArray($roleSets);
        }

        if (is_string($roleSets)) {
            return $guard
                ? $this->roleSets->where('guard_name', $guard)->contains('name', $roleSets)
                : $this->roleSets->contains('name', $roleSets);
        }

        if (is_int($roleSets)) {
            $roleSetClass = $this->getRoleSetClass();
            $key = (new $roleSetClass())->getKeyName();

            return $guard
                ? $this->roleSets->where('guard_name', $guard)->contains($key, $roleSets)
                : $this->roleSets->contains($key, $roleSets);
        }

        if ($roleSets instanceof RoleSet) {
            return $this->roleSets->contains($roleSets->getKeyName(), $roleSets->getKey());
        }

        if (is_array($roleSets)) {
            foreach ($roleSets as $roleSet) {
                if ($this->hasRoleSet($roleSet, $guard)) {
                    return true;
                }
            }

            return false;
        }

        return $roleSets->intersect($guard ? $this->roles->where('guard_name', $guard) : $this->roleSets)->isNotEmpty();
    }


    public function hasAnyRoleSet(...$roleSets): bool
    {
        return $this->hasRoleSet($roleSets);
    }


    public function hasAllRoleSets($roleSets, string $guard = null): bool
    {
        if (is_string($roleSets) && false !== strpos($roleSets, '|')) {
            $roleSets = $this->convertPipeToArray($roleSets);
        }

        if (is_string($roleSets)) {
            return $guard
                ? $this->roleSets->where('guard_name', $guard)->contains('name', $roleSets)
                : $this->roleSets->contains('name', $roleSets);
        }

        if ($roleSets instanceof RoleSet) {
            return $this->roleSets->contains($roleSets->getKeyName(), $roleSets->getKey());
        }

        $roleSets = collect()->make($roleSets)->map(function ($roleSet) {
            return $roleSet instanceof RoleSet ? $roleSet->name : $roleSet;
        });

        return $roleSets->intersect(
            $guard
                ? $this->roleSets->where('guard_name', $guard)->pluck('name')
                : $this->getRoleSetNames()
        ) == $roleSets;
    }


    public function hasExactRoleSets($roleSets, string $guard = null): bool
    {
        if (is_string($roleSets) && false !== strpos($roleSets, '|')) {
            $roleSets = $this->convertPipeToArray($roleSets);
        }

        if (is_string($roleSets)) {
            $roleSets = [$roleSets];
        }

        if ($roleSets instanceof RoleSet) {
            $roleSets = [$roleSets->name];
        }

        $roleSets = collect()->make($roleSets)->map(function ($roleSet) {
            return $roleSet instanceof Role ? $roleSet->name : $roleSet;
        });

        return $this->roleSets->count() == $roleSets->count() && $this->hasAllRoleSets($roleSets, $guard);
    }


    public function getDirectRoles(): Collection
    {
        return $this->roles;
    }

    public function getRoleSetNames(): Collection
    {
        return $this->roleSets->pluck('name');
    }

    protected function getStoredRoleSet($roleSet): RoleSet
    {
        $roleSetClass = $this->getRoleSetClass();

        if (is_numeric($roleSet)) {
            return $roleSetClass->findById($roleSet, $this->getDefaultGuardName());
        }

        if (is_string($roleSet)) {
            return $roleSetClass->findByName($roleSet, $this->getDefaultGuardName());
        }

        return $roleSet;
    }

    protected function convertPipeToArray(string $pipeString)
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2) {
            return $pipeString;
        }

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }

        if (!in_array($quoteCharacter, ["'", '"'])) {
            return explode('|', $pipeString);
        }

        return explode('|', trim($pipeString, $quoteCharacter));
    }
}
