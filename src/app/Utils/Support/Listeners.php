<?php

namespace App\Utils\Support;

class Listeners extends JsonGetSet
{
    public static function getAllOf($type, $name = "listeners")
    {
        return self::_getAllOf($type, "$name.json");
    }

    public static function setAllOf($type, $data)
    {
        return self::_setAllOf($type, $data, "listens.json");
    }
}
