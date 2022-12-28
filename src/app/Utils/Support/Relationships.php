<?php

namespace App\Utils\Support;

class Relationships extends JsonGetSet
{
    public static function getAllOf($type, $name = "relationships")
    {
        return self::_getAllOf($type, "$name.json");
    }

    public static function setAllOf($type, $data)
    {
        return self::_setAllOf($type, $data, "relationships.json");
    }
}
