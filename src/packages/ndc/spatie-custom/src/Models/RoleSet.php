<?php

namespace Ndc\SpatieCustom\Models;

use Ndc\SpatieCustom\Traits\HasRoles;
use Ndc\SpatieCustom\Traits\RefreshesRoleCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ndc\SpatieCustom\Contracts\RoleSet as ContractsRoleSet;
use Ndc\SpatieCustom\RoleRegistrar;
use Spatie\Permission\Exceptions\GuardDoesNotMatch;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Guard;

class RoleSet extends Model implements ContractsRoleSet
{
    use HasRoles;
    use RefreshesRoleCache;

    protected $guarded = [];
    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
    }

    public function getTable()
    {
        return config('permission.table_names.role_sets', parent::getTable());
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);
        $params = ['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']];
        error_log(RoleRegistrar::$teams);
        if (RoleRegistrar::$teams) {
            if (array_key_exists(RoleRegistrar::$teamsKey, $attributes)) {
                $params[RoleRegistrar::$teamsKey] = $attributes[RoleRegistrar::$teamsKey];
            } else {
                $attributes[RoleRegistrar::$teamsKey] = getRolesTeamId();
            }
        }
        if (static::findByParam($params)) {
            throw RoleAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }
        return static::query()->create($attributes);
    }

    /**
     * A role may be given various permissions.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_set_has_roles'),
            RoleRegistrar::$pivotRoleSet,
            RoleRegistrar::$pivotRole
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_role_sets'),
            RoleRegistrar::$pivotRoleSet,
            config('permission.column_names.model_morph_key')
        );
    }

    /**
     * Find a role by its name and guard name.
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role|\Spatie\Permission\Models\Role
     *
     * @throws \Spatie\Permission\Exceptions\RoleDoesNotExist
     */
    public static function findByName(string $name, $guardName = null): ContractsRoleSet
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $roleSet = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        if (!$roleSet) {
            throw RoleDoesNotExist::named($name);
        }

        return $roleSet;
    }

    /**
     * Find a role by its id (and optionally guardName).
     *
     * @param int $id
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role|\Spatie\Permission\Models\Role
     */
    public static function findById(int $id, $guardName = null): ContractsRoleSet
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $roleSet = static::findByParam([(new static())->getKeyName() => $id, 'guard_name' => $guardName]);

        if (!$roleSet) {
            throw RoleDoesNotExist::withId($id);
        }

        return $roleSet;
    }

    /**
     * Find or create role by its name (and optionally guardName).
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role|\Spatie\Permission\Models\Role
     */
    public static function findOrCreate(string $name, $guardName = null): ContractsRoleSet
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $roleSet = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        if (!$roleSet) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName] + (RoleRegistrar::$teams ? [RoleRegistrar::$teamsKey => getRolesTeamId()] : []));
        }

        return $roleSet;
    }

    protected static function findByParam(array $params = [])
    {
        $query = static::query();

        if (RoleRegistrar::$teams) {
            $query->where(function ($q) use ($params) {
                $q->whereNull(RoleRegistrar::$teamsKey)
                    ->orWhere(RoleRegistrar::$teamsKey, $params[RoleRegistrar::$teamsKey] ?? getRolesTeamId());
            });
            unset($params[RoleRegistrar::$teamsKey]);
        }

        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|Permission $permission
     *
     * @return bool
     *
     * @throws \Spatie\Permission\Exceptions\GuardDoesNotMatch
     */
    public function hasRoleTo($role): bool
    {
        if (config('permission.enable_wildcard_permission', false)) {
            return $this->hasWildcardPermission($role, $this->getDefaultGuardName());
        }

        $roleClass = $this->getRoleClass();

        if (is_string($role)) {
            $role = $roleClass->findByName($role, $this->getDefaultGuardName());
        }

        if (is_int($role)) {
            $role = $roleClass->findById($role, $this->getDefaultGuardName());
        }

        if (!$this->getGuardNames()->contains($role->guard_name)) {
            throw GuardDoesNotMatch::create($role->guard_name, $this->getGuardNames());
        }

        return $this->roles->contains($role->getKeyName(), $role->getKey());
    }
}
