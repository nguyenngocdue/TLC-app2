<?php

namespace App\Utils\Support;

use App\Models\User;
use DateTime;

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

    public static function getValueDatetimeByCurrentUser($dateTimeValue , $formatType=''){
        $currentUserTimeZone = User::find(CurrentUser::id())->time_zone;
        $dateString = DateReport::convertToTimezone($dateTimeValue, $currentUserTimeZone);
        if ($formatType) {
            $date = DateTime::createFromFormat('d-m-Y H:i:s', $dateString);
            if ($date) {
                return $date->format($formatType);
            } else {
                return $dateString;
            }
        }
        return $dateString;
    }

    public static function formatDateTime($dateString , $typeFormat='', $originalFormat='d-m-Y H:i:s'){
        if ($typeFormat) {
            $date = DateTime::createFromFormat($originalFormat, $dateString);
            if ($date) {
                return $date->format($typeFormat);
            } else {
                return $dateString;
            }
        }
        return $dateString;
    }
    
}
