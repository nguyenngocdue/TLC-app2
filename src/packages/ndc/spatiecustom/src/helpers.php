<?php

if (!function_exists('setRolesTeamId')) {
    /**
     * @param int|string|\Illuminate\Database\Eloquent\Model $id
     *
     */
    function setRolesTeamId($id)
    {
        app(\Ndc\Spatiecustom\RoleRegistrar::class)->setRolesTeamId($id);
    }
}

if (!function_exists('getRolesTeamId')) {
    /**
     * @return int|string
     */
    function getRolesTeamId()
    {
        return app(\Ndc\Spatiecustom\RoleRegistrar::class)->getRolesTeamId();
    }
}
