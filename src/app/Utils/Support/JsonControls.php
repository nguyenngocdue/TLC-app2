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

    public static function getEloquents()
    {
        return self::getAll()['eloquents'];
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
