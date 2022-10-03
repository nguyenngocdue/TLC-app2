<?php

namespace Ndc\Spatiecustom\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface RoleSet
{
    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * Find a role by its name and guard name.
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \App\Contracts\RoleSet
     *
     * @throws \App\Exceptions\RoleSetDoesNotExist
     */
    public static function findByName(string $name, $guardName): self;

    /**
     * Find a role by its id and guard name.
     *
     * @param int $id
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\RoleSet
     *
     * @throws \Spatie\Permission\Exceptions\RoleSetDoesNotExist
     */
    public static function findById(int $id, $guardName): self;

    /**
     * Find or create a RoleSet by its name and guard name.
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\RoleSet
     */
    public static function findOrCreate(string $name, $guardName): self;

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|\Spatie\Permission\Contracts\Role $role
     *
     * @return bool
     */
    public function hasRoleTo($role): bool;
}
