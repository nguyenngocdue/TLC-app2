<?php

namespace App\Utils\Support\Json;

class SuperDefinitions
{
    private static function getOf($type, $key, $defaultValue, $isScalar = false)
    {
        $superProps = SuperProps::getFor($type);
        $definitions = $superProps['settings']['definitions'] ?? [];
        if (empty($definitions)) {
            $status = $defaultValue;
        } else {
            $status = $definitions[$key];
        }
        $status = $isScalar ? $status[0] : $status;
        return $status;
    }

    static function getNewOf($type)
    {
        return static::getOf($type, 'new', ['new'], true);
    }
    static function getClosedOf($type)
    {
        return static::getOf($type, 'closed', ['closed']);
    }
    static function getReadOnlyOf($type)
    {
        return static::getOf($type, 'closed', []);
    }
    static function getHideSaveButtonOf($type)
    {
        return static::getOf($type, 'hide-save-btn', []);
    }
    static function getPostClosedOf($type)
    {
        return static::getOf($type, 'post-closed', []);
    }
}
