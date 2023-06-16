<?php

use App\Http\Controllers\Workflow\LibApps;

if (!function_exists('qr_apps_renderer')) {
    function qr_apps_renderer()
    {
        return LibApps::getByShowRenderer('qr-app-renderer');
    }
}

if (!function_exists('userIsAdmin')) {
    function userIsAdmin($user)
    {
        if (!$user->hasAnyRoleSet('admin')) {
            return false;
        }
        return true;
    }
}
