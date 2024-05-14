<?php

namespace App\Utils\Support;

class DateFormat
{
    public static function getMonthAbbreviation($month)
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        if (!is_numeric($month)) return $month;
        return date('M', strtotime("2023-$month-01"));
    }

    public static function getPeriodMonth()
    {
        $lastMonth = (int)date('m');
        $lastYear = (int)date('Y');
        $firstMonth = $lastMonth === 12 ? 1 : $lastMonth - 1;
        $firstYear = $lastMonth === 1 ? $lastYear - 1 : $lastYear;
        return [
            'first_date' => $firstYear . '-' . sprintf('%02d', $firstMonth) . '-26',
            'last_date' => $lastYear . '-' .  sprintf('%02d', $lastMonth) . '-25'
        ];
    }
}
