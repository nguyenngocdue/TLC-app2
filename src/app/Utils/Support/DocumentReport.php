<?php

namespace App\Utils\Support;

use DateTime;
use Illuminate\Support\Str;

class DocumentReport
{
    public static function getCurrentMonthYear() {
        $currentDate = new DateTime();
        $formattedDate = $currentDate->format("Y-m");
        return $formattedDate;
    }

    // public static function createManyParamsFromDates($params)
    // {
    //     $pickerDate = $params['picker_date'];
    //     $dates = explode("-", $pickerDate);
    //     [$fromDate, $toDate] = [trim($dates[0]), trim($dates[1])];
        
    //     $manyDates = PivotReport::getDatesBetween($fromDate, $toDate);
    //     $manyDates = array_map(fn ($item) => Report::formatDateString($item), $manyDates);
        
    //     $params = array_map(function ($item) use ($params) {
    //         $params['picker_date'] =  $item;
    //         return $params;
    //     }, $manyDates);
    //     return $params;
    // }

    public static function countLastItems($array) {
        $totalItemCount = 0;
        foreach ($array as $item) {
            if (is_array($item)) {
                $totalItemCount += self::countLastItems($item);
            } else {
                $totalItemCount++;
                break;
            }
        }
        return $totalItemCount;
    }
    
}
