<?php

namespace App\BigThink;

use Illuminate\Support\Facades\DB;

class Options
{
    private static $singleton = null;
    private static function getAll()
    {
        if (is_null(static::$singleton)) {
            $all = DB::table('options')->get();
            foreach ($all as $item) $indexed[$item->key][] = json_decode($item->value);
            static::$singleton = collect($indexed);
        }
        return static::$singleton;
    }

    public static function get($key, $default = null)
    {
        $db = static::getAll();
        return $db[$key] ?? $default;
    }
}
