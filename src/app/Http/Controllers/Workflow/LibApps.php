<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Log;
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
                    $app['href'] = Route::has($route) ? route($route) : "#RouteNotFound1:$route";
                    $routeCreate = Str::plural($app['name']) . ".create";
                    if (static::checkEntityHasPermission('create', $key, $permissions)) {
                        $app['href_create'] = Route::has($routeCreate) ? route($routeCreate) : "#RouteNotFound2:$routeCreate";
                    }
                    $result[$key] = $app;
                }
            }
            $reportRoutes = Report::getAllRoutes();
            $libReport = LibReports::getAll();
            foreach ($reportRoutes as $route) {
                $name = $route['name'];
                $lib = $libReport[$name];
                if ($lib['hidden'] ?? false) continue;
                $reportType = ucfirst(substr($lib['name'], 0, strpos($lib['name'], "-")));
                if (CurrentUser::isAdmin() || static::checkEntityHasPermission('read', $route['singular'], $permissions)) {
                    $item = [
                        'name' => $name,
                        'package_tab' => isset($lib['package_tab']) ? $lib['package_tab'] : "unknown package",
                        'package_rendered' => isset($lib['package']) ? Str::appTitle($lib['package']) : "unknown package",
                        'sub_package_rendered' => isset($lib['sub_package']) ? Str::appTitle($lib['sub_package']) : "unknown sub_package",
                        'title' =>  $reportType . ": " . ($lib['title'] ?? "Untitled"),
                        'href' => Route::has($route['name']) ? route($route['name']) : "#RouteNotFound1:$route",
                        'icon' => '<i class="fa-duotone fa-file-chart-column"></i>',
                    ];
                    $result[$name] = $item;
                }
            }
            static::$singleton_permission = $result;
            // dump($result);
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
                $hasRoute = Route::has($route);
                if ($hasRoute) {
                    $app['href'] = route($route);
                } else {
                    $app['nickname'] = $app['nickname'] ?? '' . " ?";
                    $app['href'] = "#RouteNotFound3:$route";
                }
                if (is_null($app['icon'] ?? null)) {
                    $app['icon'] = '<i class="fa-duotone fa-file"></i>';
                }
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
            if (isset($app['show_renderer']) && $app['show_renderer'] == $renderer) $result[] = $key;
        }
        return $result;
    }
}
