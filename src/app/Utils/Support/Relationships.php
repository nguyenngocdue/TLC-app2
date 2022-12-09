<?php

namespace App\Utils\Support;

class Relationships
{
    public static function getAllOf($type)
    {
        return JsonGetSet::getAllOf($type, "relationships.json");
    }

    public static function setAllOf($type, $data)
    {
        return JsonGetSet::setAllOf($type, $data, "relationships.json");
    }
}
