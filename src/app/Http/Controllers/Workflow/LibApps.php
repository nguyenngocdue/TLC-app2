<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class LibApps extends AbstractLib
{
    protected static $key = "apps";

    private static $singleton = null;
    public static function getAll()
    {
        if (!isset(static::$singleton)) {
            $result = parent::getAll();
            foreach ($result as &$app) {
                $app['package_rendered'] = isset($app['package']) ? Str::appTitle($app['package']) : "unknown package";
                $app['sub_package_rendered'] = isset($app['sub_package']) ? Str::appTitle($app['sub_package']) : "unknown sub_package";
                $route = Str::plural($app['name']) . ".index";
                $app['href'] = Route::has($route) ? route($route) : "#RouteNotFound:$route";
            }
            static::$singleton = $result;
        }
        return static::$singleton;
    }

    public static function getAllShowBookmark()
    {
        $allApps = static::getAll();
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
