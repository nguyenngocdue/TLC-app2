<?php

namespace App\Utils\Support;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Diginet
{
    private static function actionCreator($type, $path, $singular, $mode)
    {
        return [
            'singular' => $singular,
            'mode' => $mode,
            'type' => $type,
            'path' => $path,
            // 'routeName' => $type . '-' . $singular . "_" . $mode,
            'name' => $type . '-' . $singular . "_" . $mode,
        ];
    }

    public static function getAllRoutes()
    {
        $entities = Entities::getAll();

        $result0 = [];
        foreach ($entities as $entity) {
            $entityName = Str::getEntityName($entity);
            $singular = Str::singular($entityName);
            $ucfirstName = Str::ucfirst($singular);
            $modes = ['show_data'];
            foreach ($modes as $mode) {
                $path = "App\\Http\\Controllers\\DiginetHR\\PageController\\{$ucfirstName}_$mode";
                if (class_exists($path)) $result0[] = static::actionCreator('diginet', $path, $singular, $mode);
            }
        }
        $result1 = [];
        foreach ($result0 as $line) {
            $result1[$line['name']] = $line;
        }
        // dd($result1);
        return $result1;
    }

    public static function getRouteFromNames($names)
    {
        $routes = [];
        foreach ($names as $key => $value) {
            if (Route::has($value)) {
                $routes[$key] = route($value);
            }
        }
        return $routes;
    }
}
