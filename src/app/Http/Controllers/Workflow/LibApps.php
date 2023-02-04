<?php

namespace App\Http\Controllers\Workflow;

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
            $app['href'] = route(Str::plural($app['name']) . ".index");
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
}
