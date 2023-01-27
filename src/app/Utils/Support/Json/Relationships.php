<?php

namespace App\Utils\Support\Json;

class Relationships extends JsonGetSet
{
    protected static $filename = "relationships.json";

    public static function getColumnName($key, $relationship, $eloquentParams)
    {
        $eloquentKey = substr($key, 1);
        if ($relationship === 'belongsTo') {
            if (isset($eloquentParams[$eloquentKey][2])) return $eloquentParams[$eloquentKey][2];
        } else {
            return $eloquentKey;
        }
        return "unknown: $key [7744]";
    }
}
