<?php

if (!function_exists('setRolesTeamId')) {
    /**
     * @param int|string|\Illuminate\Database\Eloquent\Model $id
     *
     */
    function setRolesTeamId($id)
    {
        app(\Ndc\SpatieCustom\RoleRegistrar::class)->setRolesTeamId($id);
    }
}

if (!function_exists('getRolesTeamId')) {
    /**
     * @return int|string
     */
    function getRolesTeamId()
    {
        return app(\Ndc\SpatieCustom\RoleRegistrar::class)->getRolesTeamId();
    }
}
