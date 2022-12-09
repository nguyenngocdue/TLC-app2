<?php

namespace App\Utils\Support;

class Relationships extends JsonGetSet
{
    public static function getAllOf($type)
    {
        return self::_getAllOf($type, "relationships.json");
    }

    public static function setAllOf($type, $data)
    {
        return self::_setAllOf($type, $data, "relationships.json");
    }
}
