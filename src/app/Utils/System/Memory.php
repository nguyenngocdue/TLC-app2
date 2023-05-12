<?php

namespace App\Utils\System;

class Memory
{
    private static $memoryStart = 0;
    private static $memoryLast = 0;

    public static function startMemoryCounter()
    {
        self::$memoryStart = memory_get_usage();
    }

    public static function getMemory()
    {
        return self::$memoryStart;
    }

    public static function getMemoryElapse()
    {
        self::$memoryLast = round((memory_get_usage() - self::getMemory()) * 0.000001, 2);
        return self::$memoryLast;
    }

    public static function getMemoryElapseFromLast()
    {
        $bak = self::$memoryLast;
        $new = self::getMemoryElapse();
        return $new - $bak;
    }
}
