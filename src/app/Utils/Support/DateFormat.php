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
}
