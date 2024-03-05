<?php

namespace App\Utils\Support;

class StringCustomize
{
    public static function toSnakeCase($input)
    {
        $stringWithUnderscores = preg_replace('/(?<!^)[A-Z]/', '_$0', $input);
        $snakeCaseString = strtolower($stringWithUnderscores);
        return $snakeCaseString;
    }

    public static function changeNamesToField($names)
    {
        foreach ($names as &$name) $name =  self::toSnakeCase($name);
        return $names;
    }
}
