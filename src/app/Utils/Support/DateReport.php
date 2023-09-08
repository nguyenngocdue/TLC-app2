<?php

namespace App\Utils\Support;

use DateTime;

class DateReport
{
    public static function getShortDayOfWeek($dateString) {
        $date = DateTime::createFromFormat('d/m/Y', $dateString);
        $dayOfWeek = $date->format('D');
        return $dayOfWeek;
    }

    public static function explodePickerDate($pickerDate, $type='d/m/Y')
    {
        $pickerDate = array_map(fn ($item) => trim($item), explode('-', $pickerDate));
        $startDate = $pickerDate[0] ?? '01/01/2021';
        $endDate = $pickerDate[1] ?? '01/02/2021';
        if($type === 'Y-m-d'){
            $startDate = self::formatDateString($startDate, 'Y-m-d');
            $endDate = self::formatDateString($endDate, 'Y-m-d');
        }
        return [$startDate, $endDate];
    }

    public static function formatDateString($strDate, $typeFormat = 'Y-m-d')
    {
        $strDate = str_replace(['-', '_'], '/', $strDate);
        $dateTime = DateTime::createFromFormat('d/m/Y', $strDate);
        // dd($strDate, $dateTime);
        if (!$dateTime) return false;
        if ($dateTime) {
            return $dateTime->format($typeFormat);
        }
        return DateTime::createFromFormat('Y/m/d', $strDate)->format($typeFormat);
    }

    
}
