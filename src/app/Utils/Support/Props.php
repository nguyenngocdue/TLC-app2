<?php

namespace App\Utils\Support;

class Props
{
    public static function getAllOf($type)
    {
        return JsonGetSet::getAllOf($type, "props.json");
    }

    public static function setAllOf($type, $data)
    {
        return JsonGetSet::setAllOf($type, $data, "props.json");
    }
}
