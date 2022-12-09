<?php

namespace App\Utils\Support;

class Props extends JsonGetSet
{
    public static function getAllOf($type)
    {
        return self::_getAllOf($type, "props.json");
    }

    public static function setAllOf($type, $data)
    {
        return self::_setAllOf($type, $data, "props.json");
    }
}
