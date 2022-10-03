<?php

namespace Ndc\SpatieCustom\Models;

use Ndc\SpatieCustom\Traits\HasRoleSets;
use Ndc\SpatieCustom\RoleRegistrar;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as ModelsRole;
use Spatie\Permission\PermissionRegistrar;

class Role extends ModelsRole
{
    use HasRoleSets;

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
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotRole,
            PermissionRegistrar::$pivotPermission
        );
    }
}
