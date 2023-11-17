<?php

namespace App\Utils\Support;

use App\Http\Controllers\Workflow\LibApps;
use BadMethodCallException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CurrentRoute
{
    public static function getName()
    {
        return Route::currentRouteName();
    }

    public static function getTypeSingular()
    {
        try {
            $type = Route::current()->getController()->getType();
        } catch (BadMethodCallException $e) {
            return "Unknown getTypeSingular, please define \"getType\".";
        }
        return Str::singular($type);
    }

    public static function getTypePlural()
    {
        return Str::plural(self::getTypeSingular());
    }

    /** This will return index, create, edit, show,... */
    public static function getControllerAction()
    {
        $result = Route::current()->action['controller'];
        return substr($result, strpos($result, '@') + 1);
    }

    public static function getEntityId($typeSingular)
    {
        $current = Route::current();
        $typeSingular = Str::singular($typeSingular);
        // $typeSingular = $current->controller->getType();
        $result = $current->parameters[$typeSingular] ?? $current->parameters['id'] ?? null;
        return $result;
    }

    public static function getEntitySlug($typeSingular)
    {
        $current = Route::current();
        $typeSingular = Str::singular($typeSingular);
        $result = $current->parameters['slug'] ?? null;
        return $result;
    }

    /** This will return hse_incident_reports.edit */
    public static function getControllerAs()
    {
        $result = Route::current()->action['as'] ?? "unknown_controller_as";
        return $result;
    }

    public static function getTitleOf($type)
    {
        return LibApps::getFor($type)['title'];
    }

    public static function getCurrentController()
    {
        $result = Route::current()->action['controller'];
        $parserStr = explode('\\', $result);
        $str = $parserStr[Count($parserStr) - 1];
        return substr($str, 0, strpos($str, '@'));
    }
    public static function getCurrentIsTrashed()
    {
        $result = Route::current()->action['controller'];
        return substr($result, strpos($result, '@') + 1) === 'indexTrashed';
    }
}
