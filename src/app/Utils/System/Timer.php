<?php

namespace App\Utils\System;

class Timer
{
    private static $timeCounter = 0;
    private static $lastAccess = 0;

    public static function startTimeCounter()
    {
        self::$timeCounter = microtime(true);
    }

    public static function getTimeCounter()
    {
        return self::$timeCounter;
    }

    public static function getTimeElapse()
    {
        self::$lastAccess = intval(1000 * (microtime(true) - self::getTimeCounter()));
        return self::$lastAccess;
    }

    public static function getTimeElapseFromLastAccess()
    {
        $bak = self::$lastAccess;
        $new = self::getTimeElapse();
        return $new - $bak;
    }
}
