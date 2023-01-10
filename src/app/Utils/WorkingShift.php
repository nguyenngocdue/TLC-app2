<?php

namespace App\Utils;

use App\Models\Public_holiday;
use App\Utils\Constant;

class WorkingShift
{
    private static $arrayLength = 1000;

    private static function getHolidays()
    {
        $query = Public_holiday::where('workplace_id', 2)->select("ph_date");
        return $query->get()->pluck('ph_date')->toArray();
    }

    private static function getWeekends()
    {
        return  ['Sat', 'Sun'];
    }

    private static function makeWorkdays($started_at)
    {
        $result = [];
        $holidays = static::getHolidays(); //<< This is slow, should not be in loop
        // var_dump("Holidays: " . join(", ", $holidays));
        $weekends = static::getWeekends();
        for ($i = 0; $i < static::$arrayLength; $i++) {

            $nextDay = date(Constant::FORMAT_DATE_MYSQL, strtotime("+$i day", strtotime($started_at)));
            $isHoliday = in_array($nextDay, $holidays);

            $theDay = date(Constant::FORMAT_WEEKDAY_SHORT, strtotime($nextDay));
            $isWeekend = in_array($theDay, $weekends);

            if (!$isWeekend && !$isHoliday) $result[] = date(Constant::FORMAT_DATE_MYSQL, strtotime($nextDay));
        }
        return $result;
    }

    private static $singleton = null;
    public static function getWorkdays($started_at)
    {
        if (is_null(static::$singleton)) {
            static::$singleton = static::makeWorkdays($started_at);
        }
        return static::$singleton;
    }

    private static function findIndex($started_at, $workdays)
    {
        $date = date(Constant::FORMAT_DATE_MYSQL, strtotime($started_at));
        for ($i = 0; ($i < sizeof($workdays) - 1); $i++) {
            // var_dump($workdays[$i] . " < " . $date . " < " . $workdays[$i + 1]);
            if ($workdays[$i] === $date) return $i;
            if ($workdays[$i] < $date && $date < $workdays[$i + 1]) return $i + 1;
        }
        return 0;
    }

    private static function getNextHour($started_at, $work_hours,)
    {
        $s = date(Constant::FORMAT_TIME_MYSQL, strtotime($started_at));
        // 07:30 -> 7.5
        $startHourDecimal0 = substr($s, 0, 2) + substr($s, 3, 2) / 60;
        //Map 12.5..16.0 to 11.5..15.0 
        $startHourDecimal1 = ($startHourDecimal0 >= 12.5) ? $startHourDecimal0 - 1 : $startHourDecimal0;
        //Map 7..15 to 0..8
        $startHourDecimal2 = $startHourDecimal1 - 7;

        //Result will be in range 0..8
        $nextHourDecimal0 = ($work_hours + $startHourDecimal2) % 8;

        //Map 0..8 to 7..15
        $nextHourDecimal1 = $nextHourDecimal0 + 7;
        //Map 11.5..15.0 to 12.5..16.0
        $nextHourDecimal2 = ($nextHourDecimal1 >= 11.5) ? $nextHourDecimal1 + 1 : $nextHourDecimal1;

        //Map 7.5 --> 7:30
        $nextHour = str_pad($nextHourDecimal2, 2, "0", STR_PAD_LEFT) . ":" . str_pad(($nextHourDecimal2 % 1) * 60, 2, "0", STR_PAD_LEFT);
        // var_dump("$s $startHourDecimal0 -> $startHourDecimal1 -> $startHourDecimal2 + $work_hours = [$nextHourDecimal0] -> $nextHourDecimal1 -> $nextHourDecimal2 -> $nextHour");
        return $nextHour . ":00";
    }

    public static function getNextWorkingDateTime($started_at, $work_hours, $workdays)
    {
        $toBeAddedDays = floor($work_hours / 8);
        $index = static::findIndex($started_at, $workdays);
        // var_dump("$started_at, $index, $toBeAddedDays");
        $nextDay = $workdays[$index + $toBeAddedDays];

        // $endDate = date(Constant::FORMAT_DATE_MYSQL, strtotime("+$work_hours days", strtotime($started_at)));
        $nextHour = static::getNextHour($started_at, $work_hours);

        // var_dump("$started_at, $end, $work_hours, $nextDay");
        return $nextDay . " " . $nextHour;
    }
}
