<?php


namespace App\Utils\Support;

use Illuminate\Support\Str;

class Erp
{
    static function cleanString($string)
    {
        // Use preg_replace to remove \u0000 and * characters
        return preg_replace('/\x00|\*/', '', $string);
    }

    static function getModelPath($type)
    {
        $str = Str::modelPathFrom($type) . "_external";
        return (class_exists($str)) ? $str : null;
    }

    static function getAllColumns($type, $sorted = false)
    {
        $modelPath = static::getModelPath($type);
        if (!$modelPath) return [];
        $result = $modelPath::query();
        $columns = array_keys((array) $result->first()->getOriginal());
        $columns = array_map(fn($col) => static::cleanString($col), $columns);
        if ($sorted) sort($columns);
        return $columns;
    }
}
