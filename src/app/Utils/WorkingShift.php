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
        // $currentDate = $started_at;
        $holidays = static::getHolidays(); //<< This is slow, should not be in loop
        var_dump("Holidays: " . join(", ", $holidays));
        $weekends = static::getWeekends();
        for ($i = 0; $i < static::$arrayLength; $i++) {

            $nextDay = date(Constant::FORMAT_DATE_MYSQL, strtotime("+$i day", strtotime($started_at)));
            $isHoliday = in_array($nextDay, $holidays);

            $theDay = date(Constant::FORMAT_WEEKDAY_SHORT, strtotime($nextDay));
            $isWeekend = in_array($theDay, $weekends);

            if (!$isWeekend && !$isHoliday) $result[] = date(Constant::FORMAT_DATE_MYSQL, strtotime($nextDay));
            // $currentDate = $nextDay;
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

    private static function findIndex($started_at, $work_hours, $workdays)
    {
        $date = date(Constant::FORMAT_DATE_MYSQL, strtotime($started_at));
        $index = array_search($date, $workdays, true);
        if ($index === false) {
            for ($i = 0; $i < sizeof($workdays); $i++) {
                if ($workdays[$i] === $date) return $i;
            }
        }
        return $index;
    }

    public static function getNextWorkingDateTime($started_at, $work_hours, $workdays)
    {
        $toBeAddedDays = floor($work_hours / 8);
        $index = static::findIndex($started_at, $work_hours, $workdays);
        var_dump("$started_at, $index, $toBeAddedDays");
        $nextDay = $workdays[$index + $toBeAddedDays];

        // $nextDayTS = strtotime("+$toBeAddedDays days", strtotime($started_at));
        // $nextDay = date(Constant::FORMAT_DATE_MYSQL, $nextDayTS);
        $end = date(Constant::FORMAT_DATETIME_MYSQL, strtotime("+$work_hours days", strtotime($started_at)));

        var_dump("$started_at, $end, $work_hours, $nextDay");
        return $end;
    }
}
