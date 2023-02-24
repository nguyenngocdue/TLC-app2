<?php

namespace App\Utils;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class CacheToRamForThisSection
{
    static $singleton = [];
    private static function getExpensive($key, $param, $fn)
    {
        if (App::isLocal()) return $fn($param);
        if (!Cache::has($key)) {
            Cache::rememberForever($key, fn () => $fn($param));
        }
        return Cache::get($key);
    }

    static function get($key, $param, $fn)
    {
        if (!isset(static::$singleton[$key])) {
            static::$singleton[$key] = static::getExpensive($key, $param, $fn);
        }
        return static::$singleton[$key];
    }
}
