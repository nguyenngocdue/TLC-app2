<?php

namespace App\Utils;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class CacheToRamForThisSection
{
    static $singleton = [];
    public static function forget($key, $subKey = null)
    {
        if (is_null($subKey))
            Cache::forget($key);
        else
            Cache::tags([$key])->flush();
    }

    private static function getExpensive($key, $fn, $subKey)
    {
        if (App::isLocal()) return $fn();
        //If no subKey is provided, use key name to cache
        //If a subKey is provided, use tags
        if (is_null($subKey)) {
            if (!Cache::has($key)) {
                Cache::rememberForever($key, fn () => $fn());
            }
            return Cache::get($key);
        } else {
            if (!Cache::tags([$key])->has($subKey)) Cache::tags([$key])->put($subKey, $fn());
            return Cache::tags([$key])->get($subKey);
        }
    }

    static function get($key, $fn, $subKey = null)
    {
        $longKey = $key . "_" . $subKey;
        if (!isset(static::$singleton[$longKey])) {
            static::$singleton[$longKey] = static::getExpensive($key, $fn, $subKey);
        }
        return static::$singleton[$longKey];
    }
}
