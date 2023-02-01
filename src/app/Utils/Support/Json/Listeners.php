<?php

namespace App\Utils\Support\Json;

class Listeners extends JsonGetSet
{
    protected static $filename = "listeners.json";

    public static function getAllOf($type)
    {
        $json = parent::getAllOf($type);
        $result = [];
        foreach ($json as $key => $row) {
            if ($row['listen_action']) $result[$key] = $row;
        }
        return $result;
    }
}
