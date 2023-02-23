<?php

namespace App\Http\Controllers\Workflow;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
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

    private static function getAllExpensive()
    {
        $pathFrom = storage_path('json/' . static::getJsonPath());
        if (!file_exists($pathFrom)) file_put_contents($pathFrom, "{}");
        $json = json_decode(file_get_contents($pathFrom, true), true);
        return $json;
    }

    public static function getAll()
    {
        if (App::isLocal()) return static::getAllExpensive();
        $key = static::$key . '_of_the_app';
        if (!Cache::has($key)) {
            Cache::rememberForever($key, fn () => static::getAllExpensive());
        }
        return Cache::get($key);
    }

    public static function setAll($dataSource)
    {
        static::invalidateCache();
        $str = json_encode($dataSource, JSON_PRETTY_PRINT);
        return Storage::disk('json')->put(static::getJsonPath(), $str);
    }

    public static function invalidateCache()
    {
        if (App::isLocal()) return;
        Cache::forget(static::$key . '_of_the_app');
    }
}
