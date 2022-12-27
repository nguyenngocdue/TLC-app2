<?php

namespace App\Utils\Support;

use Illuminate\Support\Arr;

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

    public static function move(&$json, $direction, $key)
    {
        $json = array_values($json);
        for ($index = 0; $index < sizeof($json); $index++) if ($json[$index]['name'] === $key) break;
        $json = Arr::moveDirection($json, $direction, $index, $key);
        $json = Arr::keyBy($json, "name");
    }

    public static function moveTo(&$json, $newIndex, $key)
    {
        // dump($newIndex . " - " . $key);
        $json = array_values($json);
        // dump($json);
        for ($index = 0; $index < sizeof($json); $index++) if ($json[$index]['name'] === $key) break;
        $json = Arr::moveTo($json, $index, $newIndex);
        // dump($json);
        $json = Arr::keyBy($json, "name");
    }
}
