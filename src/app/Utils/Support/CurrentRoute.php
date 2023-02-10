<?php

namespace App\Utils\Support;

use App\Http\Controllers\Workflow\LibApps;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CurrentRoute
{
    public static function getTypeSingular()
    {
        $type = Route::current()->getController()->getType();
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

    /** This will return entity name: Hse_incident_report ... */
    public static function getTypeController()
    {
        $result = Route::current()->action['controller'];
        $parserStr = explode('\\', $result);
        return $parserStr[4];
    }

    public static function getEntityId($typeSingular)
    {
        $current = Route::current();
        $typeSingular = Str::singular($typeSingular);
        // $typeSingular = $current->controller->getType();
        $result = $current->parameters[$typeSingular] ?? null;
        return $result;
    }

    /** This will return hse_incident_reports.edit */
    public static function getControllerAs()
    {
        $result = Route::current()->action['as'];
        return $result;
    }

    public static function getTitleOf($type)
    {
        return LibApps::getFor($type)['title'];
    }
}
