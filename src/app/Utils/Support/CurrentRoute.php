<?php

namespace App\Utils\Support;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CurrentRoute
{
    public static function getTypePluralPretty()
    {
        return Str::headline(self::getTypePlural());
    }

    public static function getTypeSingular()
    {
        $type = Route::current()->getController()->getType();
        return Str::singular($type);
    }

    public static function getTypePlural()
    {
        return Str::plural(self::getTypeSingular());
    }

    public static function getControllerAction()
    {
        $result = Route::current()->action['controller'];
        return substr($result, strpos($result, '@') + 1);
    }

    public static function getControllerAs()
    {
        $result = Route::current()->action['as'];
        return $result;
    }
}
