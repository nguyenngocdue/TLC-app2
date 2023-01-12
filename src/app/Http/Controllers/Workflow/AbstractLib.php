<?php

namespace App\Http\Controllers\Workflow;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AbstractLib
{
    protected static $key = "abstract_lib";
    private static function getJsonPath()
    {
        return "workflow/" . static::$key . ".json";
    }

    public static function getAll()
    {
        if (!Cache::has(static::$key . '_of_the_app')) {
            $pathFrom = storage_path('json/' . static::getJsonPath());
            if (!file_exists($pathFrom)) file_put_contents($pathFrom, "{}");
            $json = json_decode(file_get_contents($pathFrom, true), true);
            Cache::rememberForever(static::$key . '_of_the_app', fn () => $json);
        }
        return Cache::get(static::$key . '_of_the_app');
    }

    public static function setAll($dataSource)
    {
        $str = json_encode($dataSource, JSON_PRETTY_PRINT);
        Cache::forget(static::$key . '_of_the_app');
        return Storage::disk('json')->put(static::getJsonPath(), $str);
    }
}
