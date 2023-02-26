<?php

namespace App\Utils\Support\Json;

use Illuminate\Support\Str;

class Listeners extends JsonGetSet
{
    protected static $filename = "listeners.json";

    public static function getAllOf($type)
    {
        $json = parent::getAllOf($type);
        $result = [];
        foreach ($json as $key => $row) {
            $row['triggers'] = Str::parseArray($row['triggers']);
            $row['listen_to_fields'] = Str::parseArray($row['listen_to_fields']);
            $row['listen_to_attrs'] = Str::parseArray($row['listen_to_attrs']);
            $row['attrs_to_compare'] = Str::parseArray($row['attrs_to_compare'] ?? 'id'); //<<CONFIG_MIGRATE
            if ($row['listen_action']) $result[$key] = $row;
        }
        return $result;
    }
}
