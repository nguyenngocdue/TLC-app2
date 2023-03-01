<?php

namespace App\Utils;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class CacheToRamForThisSection
{
    static $singleton = [];
    private static function getExpensive($key, $fn)
    {
        if (App::isLocal()) return $fn();
        if (!Cache::has($key)) {
            Cache::rememberForever($key, fn () => $fn());
        }
        return Cache::get($key);
    }

    static function get($key, $fn)
    {
        if (!isset(static::$singleton[$key])) {
            static::$singleton[$key] = static::getExpensive($key, $fn);
        }
        return static::$singleton[$key];
    }
}
