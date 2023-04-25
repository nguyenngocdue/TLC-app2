<?php

namespace App\Http\Controllers\Workflow;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class LibApps extends AbstractLib
{
    protected static $key = "apps";

    public static function getAll()
    {
        $result = parent::getAll();
        foreach ($result as &$app) {
            $app['package_rendered'] = isset($app['package']) ? Str::appTitle($app['package']) : "unknown package";
            $app['sub_package_rendered'] = isset($app['sub_package']) ? Str::appTitle($app['sub_package']) : "unknown sub_package";
            $route = Str::plural($app['name']) . ".index";
            $app['href'] = Route::has($route) ? route($route) /*. "#Found:" . $route*/ : "#RouteNotFound:$route";
        }
        return $result;
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

    public static function getByShowRenderer($showRenderer)
    {
        $apps = static::getAll();
        $result = [];
        foreach ($apps as $key => $app) {
            if ($app['show_renderer'] == $showRenderer) $result[] = $key;
        }
        return $result;
    }
}
