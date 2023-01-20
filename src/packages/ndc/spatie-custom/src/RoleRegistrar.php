<?php

namespace Ndc\SpatieCustom;

use Ndc\SpatieCustom\Contracts\RoleSet;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Contracts\Role;

class RoleRegistrar
{
    /** @var \Illuminate\Contracts\Cache\Repository */
    protected $cache;

    /** @var \Illuminate\Cache\CacheManager */
    protected $cacheManager;

    /** @var string */
    protected $roleSetClass;

    /** @var string */
    protected $roleClass;

    /** @var \Illuminate\Database\Eloquent\Collection */
    protected $roles;

    /** @var string */
    public static $pivotRole;

    /** @var string */
    public static $pivotRoleSet;

    /** @var \DateInterval|int */
    public static $cacheExpirationTime;

    /** @var bool */
    public static $teams;

    /** @var string */
    public static $teamsKey;

    /** @var int|string */
    protected $teamId = null;

    /** @var string */
    public static $cacheKey;

    /** @var array */
    private $cachedRoleSets = [];

    /** @var array */
    private $alias = [];

    /** @var array */
    private $except = [];

    /**
     * PermissionRegistrar constructor.
     *
     * @param \Illuminate\Cache\CacheManager $cacheManager
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->roleClass = config('permission.models.role');
        $this->roleSetClass = config('permission.models.role_set');
        $this->cacheManager = $cacheManager;
        $this->initializeCache();
    }

    public function initializeCache()
    {
        self::$cacheExpirationTime = config('permission.cache.expiration_time') ?: \DateInterval::createFromDateString('24 hours');
        self::$teams = config('permission.teams', false);
        self::$teamsKey = config('permission.column_names.team_foreign_key');
        self::$cacheKey = config('permission.cache.key');
        self::$pivotRole = config('permission.column_names.role_pivot_key') ?: 'role_id';
        self::$pivotRoleSet = config('permission.column_names.role_set_pivot_key') ?: 'role_set_id';
        $this->cache = $this->getCacheStoreFromConfig();
    }

    protected function getCacheStoreFromConfig(): Repository
    {
        // the 'default' fallback here is from the permission.php config file,
        // where 'default' means to use config(cache.default)
        $cacheDriver = config('permission.cache.store', 'default');

        // when 'default' is specified, no action is required since we already have the default instance
        if ($cacheDriver === 'default') {
            return $this->cacheManager->store();
        }

        // if an undefined cache store is specified, fallback to 'array' which is Laravel's closest equiv to 'none'
        if (!\array_key_exists($cacheDriver, config('cache.stores'))) {
            $cacheDriver = 'array';
        }

        return $this->cacheManager->store($cacheDriver);
    }

    /**
     * Set the team id for teams/groups support, this id is used when querying permissions/roles
     *
     * @param int|string|\Illuminate\Database\Eloquent\Model $id
     */
    public function setRolesTeamId($id)
    {
        if ($id instanceof \Illuminate\Database\Eloquent\Model) {
            $id = $id->getKey();
        }
        $this->teamId = $id;
    }

    /**
     *
     * @return int|string
     */
    public function getRolesTeamId()
    {
        return $this->teamId;
    }

    /**
     * Register the permission check method on the gate.
     * We resolve the Gate fresh here, for benefit of long-running instances.
     *
     * @return bool
     */
    public function registerRoles(): bool
    {
        app(Gate::class)->before(function (Authorizable $user, string $ability) {
            if (method_exists($user, 'checkRoleTo')) {
                return $user->checkRoleTo($ability) ?: null;
            }
        });

        return true;
    }

    /**
     * Flush the cache.
     */
    public function forgetCachedRoles()
    {
        $this->roles = null;
        return $this->cache->forget(self::$cacheKey);
    }

    /**
     * Clear class permissions.
     * This is only intended to be called by the PermissionServiceProvider on boot,
     * so that long-running instances like Swoole don't keep old data in memory.
     */
    public function clearClassRoles()
    {
        $this->roles = null;
    }

    /**
     * Load permissions from cache
     * This get cache and turns array into \Illuminate\Database\Eloquent\Collection
     */
    private function loadRoles()
    {
        if ($this->roles) {
            return;
        }

        $this->roles = $this->cache->remember(self::$cacheKey, self::$cacheExpirationTime, function () {
            return $this->getSerializedRolesForCache();
        });

        // fallback for old cache method, must be removed on next mayor version
        if (!isset($this->roles['alias'])) {
            $this->forgetCachedRoles();
            $this->loadRoles();

            return;
        }

        $this->alias = $this->roles['alias'];

        $this->hydrateRoleSetsCache();

        $this->roles = $this->getHydratedRoleCollection();

        $this->cachedRoleSets = $this->alias = $this->except = [];
    }

    /**
     * Get the permissions based on the passed params.
     *
     * @param array $params
     * @param bool $onlyOne
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRoles(array $params = [], bool $onlyOne = false): Collection
    {
        $this->loadRoles();

        $method = $onlyOne ? 'first' : 'filter';

        $roles = $this->roles->$method(static function ($role) use ($params) {
            foreach ($params as $attr => $value) {
                if ($role->getAttribute($attr) != $value) {
                    return false;
                }
            }

            return true;
        });

        if ($onlyOne) {
            $roles = new Collection($roles ? [$roles] : []);
        }

        return $roles;
    }

    /**
     * Get an instance of the permission class.
     *
     * @return \Spatie\Permission\Contracts\Permission
     */
    public function getRoleClass(): Role
    {
        return app($this->roleClass);
    }

    public function setRoleClass($roleClass)
    {
        $this->roleClass = $roleClass;
        config()->set('permission.models.role', $roleClass);
        app()->bind(Role::class, $roleClass);

        return $this;
    }

    /**
     * Get an instance of the role class.
     *
     * @return \Spatie\Permission\Contracts\Role
     */
    public function getRoleSetClass(): RoleSet
    {
        return app($this->roleSetClass);
    }

    public function setRoleSetClass($roleSetClass)
    {
        $this->roleSetClass = $roleSetClass;
        config()->set('permission.models.role_set',  $roleSetClass);
        app()->bind(RoleSet::class, $roleSetClass);

        return $this;
    }

    public function getCacheRepository(): Repository
    {
        return $this->cache;
    }

    public function getCacheStore(): Store
    {
        return $this->cache->getStore();
    }

    /**
     * Changes array keys with alias
     *
     * @return array
     */
    private function aliasedArray($model): array
    {
        return collect(is_array($model) ? $model : $model->getAttributes())->except($this->except)
            ->keyBy(function ($value, $key) {
                return $this->alias[$key] ?? $key;
            })->all();
    }

    /**
     * Array for cache alias
     */
    private function aliasModelFields($newKeys = []): void
    {
        $i = 0;
        $alphas = !count($this->alias) ? range('a', 'h') : range('j', 'p');

        foreach (array_keys($newKeys->getAttributes()) as $value) {
            if (!isset($this->alias[$value])) {
                $this->alias[$value] = $alphas[$i++] ?? $value;
            }
        }

        $this->alias = array_diff_key($this->alias, array_flip($this->except));
    }

    /*
     * Make the cache smaller using an array with only required fields
     */
    private function getSerializedRolesForCache()
    {
        $this->except = config('permission.cache.column_names_except', ['created_at', 'updated_at', 'deleted_at']);

        $roles = $this->getRoleClass()->select()->with('role_sets')->get()
            ->map(function ($role) {
                if (!$this->alias) {
                    $this->aliasModelFields($role);
                }

                return $this->aliasedArray($role) + $this->getSerializedRoleSetRelation($role);
            })->all();
        $roles = array_values($this->cachedRoleSets);
        $this->cachedRoleSets = [];

        return ['alias' => array_flip($this->alias)] + compact('roles', 'role_sets');
    }

    private function getSerializedRoleSetRelation($role)
    {
        if (!$role->roleSets->count()) {
            return [];
        }

        if (!isset($this->alias['role_sets'])) {
            $this->alias['role_sets'] = 'r';
            $this->aliasModelFields($role->roleSets[0]);
        }

        return [
            'r' => $role->roleSets->map(function ($roleSet) {
                if (!isset($this->cachedRoleSets[$roleSet->getKey()])) {
                    $this->cachedRoleSets[$roleSet->getKey()] = $this->aliasedArray($roleSet);
                }

                return $roleSet->getKey();
            })->all(),
        ];
    }

    private function getHydratedRoleCollection()
    {
        $roleClass = $this->getRoleClass();
        $roleInstance = new $roleClass();

        return Collection::make(
            array_map(function ($item) use ($roleInstance) {
                return $roleInstance
                    ->newFromBuilder($this->aliasedArray(array_diff_key($item, ['r' => 0])))
                    ->setRelation('role_sets', $this->getHydratedRoleSetCollection($item['r'] ?? []));
            }, $this->roles['roles'])
        );
    }

    private function getHydratedRoleSetCollection(array $roleSets)
    {
        return Collection::make(array_values(
            array_intersect_key($this->cachedRoleSets, array_flip($roleSets))
        ));
    }

    private function hydrateRoleSetsCache()
    {
        $roleSetClass = $this->getRoleSetClass();
        $roleSetInstance = new $roleSetClass();

        array_map(function ($item) use ($roleSetInstance) {
            $roleSet = $roleSetInstance->newFromBuilder($this->aliasedArray($item));
            $this->cachedRoleSets[$roleSet->getKey()] = $roleSet;
        }, $this->roles['role_sets']);

        $this->roles['role_sets'] = [];
    }
}
