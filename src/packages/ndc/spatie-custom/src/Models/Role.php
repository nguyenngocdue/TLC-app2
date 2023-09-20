<?php

namespace Ndc\SpatieCustom\Models;

use App\Models\Role as ModelsRole;
use Illuminate\Database\Eloquent\Model;
use Ndc\SpatieCustom\RoleRegistrar;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ndc\SpatieCustom\Contracts\Role as ContractsRole;
use Ndc\SpatieCustom\Traits\HasRoleSets as TraitsHasRoleSets;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Exceptions\GuardDoesNotMatch;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class Role extends Model implements ContractsRole
{
    use HasPermissions;
    use RefreshesPermissionCache;
    use TraitsHasRoleSets;
    static $singletonDbUserCollection = null;
    public static function getCollection()
    {
        if (!isset(static::$singletonDbUserCollection)) {
            $all = static::all();
            foreach ($all as $item) $indexed[$item->id] = $item;
            static::$singletonDbUserCollection = collect($indexed);
        }
        return static::$singletonDbUserCollection;
    }

    public static function findFromCache($id)
    {
        // if(!isset(static::getCollection()[$id]))
        return static::getCollection()[$id] ?? null;
    }
    public function givePermissionTo(...$permissions)
    {
        $permissions = collect($permissions)
            ->flatten()
            ->reduce(function ($array, $permission) {
                if (empty($permission)) {
                    return $array;
                }

                $permission = $this->getStoredPermission($permission);
                if (!$permission instanceof Permission) {
                    return $array;
                }

                $this->ensureModelSharesGuard($permission);

                $array[$permission->getKey()] = PermissionRegistrar::$teams && !is_a($this, Role::class) ?
                    [PermissionRegistrar::$teamsKey => getPermissionsTeamId()] : [];

                return $array;
            }, []);

        $model = $this->getModel();

        if ($model->exists) {
            $this->permissions()->sync($permissions, false);
            $model->load('permissions');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($permissions, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }
                    $model->permissions()->sync($permissions, false);
                    $model->load('permissions');
                }
            );
        }
        if (!is_a($this, get_class(app(PermissionRegistrar::class)->getRoleClass()))) {
            $this->forgetCachedPermissions();
        }
        return $this;
    }
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


    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
    }

    public function getTable()
    {
        return config('permission.table_names.roles', parent::getTable());
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $params = ['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']];
        if (PermissionRegistrar::$teams) {
            if (array_key_exists(PermissionRegistrar::$teamsKey, $attributes)) {
                $params[PermissionRegistrar::$teamsKey] = $attributes[PermissionRegistrar::$teamsKey];
            } else {
                $attributes[PermissionRegistrar::$teamsKey] = getPermissionsTeamId();
            }
        }
        if (static::findByParam($params)) {
            throw RoleAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_roles'),
            PermissionRegistrar::$pivotRole,
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
    public static function findByName(string $name, $guardName = null): ContractsRole
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        if (!$role) {
            throw RoleDoesNotExist::named($name);
        }

        return $role;
    }

    /**
     * Find a role by its id (and optionally guardName).
     *
     * @param  $id
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role|\Spatie\Permission\Models\Role
     */
    public static function findById($id, $guardName = null): ContractsRole
    {
        $role = static::findFromCache($id);
        if($role) return $role;
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $role = static::findByParam([(new static())->getKeyName() => $id, 'guard_name' => $guardName]);
        if (!$role) {
            throw RoleDoesNotExist::withId($id);
        }
        return $role;
    }

    /**
     * Find or create role by its name (and optionally guardName).
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role|\Spatie\Permission\Models\Role
     */
    public static function findOrCreate(string $name, $guardName = null): ContractsRole
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        if (!$role) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName] + (PermissionRegistrar::$teams ? [PermissionRegistrar::$teamsKey => getPermissionsTeamId()] : []));
        }

        return $role;
    }

    protected static function findByParam(array $params = [])
    {
        $query = static::query();

        if (PermissionRegistrar::$teams) {
            $query->where(function ($q) use ($params) {
                $q->whereNull(PermissionRegistrar::$teamsKey)
                    ->orWhere(PermissionRegistrar::$teamsKey, $params[PermissionRegistrar::$teamsKey] ?? getPermissionsTeamId());
            });
            unset($params[PermissionRegistrar::$teamsKey]);
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
    public function hasPermissionTo($permission): bool
    {
        if (config('permission.enable_wildcard_permission', false)) {
            return $this->hasWildcardPermission($permission, $this->getDefaultGuardName());
        }

        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission, $this->getDefaultGuardName());
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        }

        if (!$this->getGuardNames()->contains($permission->guard_name)) {
            throw GuardDoesNotMatch::create($permission->guard_name, $this->getGuardNames());
        }
        return $this->permissions->contains($permission->getKeyName(), $permission->getKey());
    }
}
