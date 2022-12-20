<?php

namespace App\Utils\Support;

class JsonControls
{
    private static $statuses_path = "configs/view/dashboard/controls.json";
    private static function getAll()
    {
        $pathFrom = storage_path('json/' . self::$statuses_path);
        $json = json_decode(file_get_contents($pathFrom, true), true);
        return $json;
    }

    public static function getViewAllEloquents()
    {
        return self::getAll()['view_all_eloquents'];
    }

    public static function getViewAllOracies()
    {
        return self::getAll()['view_all_oracies'];
    }

    public static function getManagePropEloquents()
    {
        return self::getAll()['manage_prop_eloquents'];
    }

    public static function getManagePropOracies()
    {
        return self::getAll()['manage_prop_oracies'];
    }

    public static function getControls()
    {
        return self::getAll()['controls'];
    }

    public static function getRendererViewAll()
    {
        return self::getAll()['renderer_view_all'];
    }

    public static function getRendererEdit()
    {
        return self::getAll()['renderer_edit'];
    }
}
