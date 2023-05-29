<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class LibApps extends AbstractLib
{
    protected static $key = "apps";

    private static $singleton = null;

    private static $singleton_permission = null;


    public static function getAllByPermission()
    {
        $permissions = CurrentUser::getPermissions();
        if (!isset(static::$singleton_permission)) {
            $lipApps = parent::getAll();
            $result = [];
            foreach ($lipApps as $key => &$app) {
                if (CurrentUser::isAdmin() || static::checkEntityHasPermission('read', $key, $permissions)) {
                    $app['package_rendered'] = isset($app['package']) ? Str::appTitle($app['package']) : "unknown package";
                    $app['sub_package_rendered'] = isset($app['sub_package']) ? Str::appTitle($app['sub_package']) : "unknown sub_package";
                    $route = Str::plural($app['name']) . ".index";
                    $routeCreate = Str::plural($app['name']) . ".create";
                    $app['href'] = Route::has($route) ? route($route) : "#RouteNotFound:$route";
                    if (static::checkEntityHasPermission('create', $key, $permissions)) {
                        $app['href_create'] = Route::has($routeCreate) ? route($routeCreate) : "#RouteNotFound:$routeCreate";
                    }
                    $result[$key] = $app;
                }
            }
            static::$singleton_permission = $result;
        }
        return static::$singleton_permission;
    }
    public static function getAll()
    {
        $result = parent::getAll();
        if (!isset(static::$singleton)) {
            foreach ($result as &$app) {
                $app['package_rendered'] = isset($app['package']) ? Str::appTitle($app['package']) : "unknown package";
                $app['sub_package_rendered'] = isset($app['sub_package']) ? Str::appTitle($app['sub_package']) : "unknown sub_package";
                $route = Str::plural($app['name']) . ".index";
                $app['href'] = Route::has($route) ? route($route) /*. "#Found:" . $route*/ : "#RouteNotFound:$route";
            }
            static::$singleton = $result;
        }
        return static::$singleton;
    }
    private static function checkEntityHasPermission($type, $entity, $permissions)
    {
        return in_array($type . '-' . Str::plural($entity), $permissions);
    }

    public static function getAllShowBookmark()
    {
        $allApps = static::getAllByPermission();
        $bookmarkSettings = CurrentUser::bookmark();
        array_walk($allApps, function (&$value, $key) use ($bookmarkSettings) {
            if (in_array($key, $bookmarkSettings)) {
                $value['bookmark'] = true;
            } else {
                $value['bookmark'] = false;
            }
        });
        return $allApps;
    }
    public static function getAllNavbarBookmark()
    {
        return array_filter(static::getAllShowBookmark(), function ($item) {
            $isHiddenNavbar = $item['hidden_navbar'] ?? false;
            return !$isHiddenNavbar;
        });
    }

    public static function getFor($entityType)
    {
        $allApps = static::getAll();
        $singular = Str::singular($entityType);
        if (!isset($allApps[$singular])) return ['title' => Str::upper("$singular is missing in LibApps")];
        $item = $allApps[$singular];
        return $item;
    }

    // public static function getByEditRenderer($editRenderer){
    // }

    public static function getByShowRenderer($renderer)
    {
        $apps = static::getAll();
        $result = [];
        foreach ($apps as $key => $app) {
            if ($app['show_renderer'] == $renderer) $result[] = $key;
        }
        return $result;
    }
}
