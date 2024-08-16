<?php

namespace App\BigThink;

trait HasShowOnScreens
{

    private static $showIsShowOn = false;
    function isShowOn($type)
    {
        $tableName = $this->getTable();
        $allow = $this->getScreensShowMeOn->pluck('id')->toArray();
        $key = "production.$tableName.$type";
        $config = config($key);
        if (is_null($config) && !static::$showIsShowOn) {
            static::$showIsShowOn = true;
            dump($type . " is not registered for Filter of [$tableName].");
        }
        return in_array($config, $allow);
    }

    // private static $showIsHideOn = false;
    // function isHideOn($type)
    // {
    //     $tableName = $this->getTable();
    //     $allow = $this->getScreensHideMeOn->pluck('id')->toArray();
    //     $key = "production.$tableName.$type";
    //     $config = config($key);
    //     if (is_null($config) && !static::$showIsShowOn) {
    //         static::$showIsHideOn = true;
    //         dump($type . " is not registered for Filter of [$tableName].");
    //     }
    //     return in_array($config, $allow);
    // }
}
