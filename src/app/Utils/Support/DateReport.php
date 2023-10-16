<?php

namespace App\Utils\Support;

use DateTime;
use Exception;

class DateReport
{
    public static function getShortDayOfWeek($dateString)
    {
        $date = DateTime::createFromFormat('d/m/Y', $dateString);
        $dayOfWeek = $date->format('D');
        return $dayOfWeek;
    }

    public static function explodePickerDate($pickerDate, $type = 'd/m/Y')
    {
        $pickerDate = array_map(fn ($item) => trim($item), explode('-', $pickerDate));
        $startDate = $pickerDate[0] ?? '01/01/2021';
        $endDate = $pickerDate[1] ?? '01/02/2021';
        if ($type === 'Y-m-d') {
            $startDate = self::formatDateString($startDate, 'Y-m-d');
            $endDate = self::formatDateString($endDate, 'Y-m-d');
        }
        return [$startDate, $endDate];
    }

    public static function formatDateString($strDate, $typeFormat = 'Y-m-d')
    {
        $strDateEdit = str_replace(['-', '_'], '/', $strDate);
        $dateTime = DateTime::createFromFormat('d/m/Y', $strDate);
        if (!$dateTime) return $strDate;
        if ($dateTime) {
            return $dateTime->format($typeFormat);
        }
        return DateTime::createFromFormat('Y/m/d', $strDateEdit)->format($typeFormat);
    }

    public static function getMonthAbbreviation($month)
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        return date('M', strtotime("2023-$month-01"));
    }

    public static function getCurrentYearAndMonth($separate = false)
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        return $separate ? 
            array('year' => $currentYear, 'month' => $currentMonth) :
            $currentYear . '-' . $currentMonth;
    }

    private static function separateStrPickerDate($strPickerDate){
        // dd($strPickerDate);
        try {
            $dates = explode("-", $strPickerDate);
            return  ['start' => self::formatDateString(trim($dates[0])), 'end' =>self::formatDateString(trim($dates[1]))];
        } catch (Exception $e){
            dd($e, $strPickerDate);
        }
    }

    public static function createValueForParams($fields, $params)
    {
        $valParams = [];
        foreach ($fields as $field) {
            if(isset($params[$field]) &&  is_array($params[$field])){
                $valParams[$field] = isset($params[$field]) ? StringReport::arrayToJsonWithSingleQuotes($params[$field],null,null): '';
            }
            else {
                $valParams[$field] = isset($params[$field]) ?  $params[$field] : '';
            }
            if($field = 'picker_date' && isset($params['picker_date'])){
                $parseDate = self::separateStrPickerDate($params['picker_date']);
                $valParams[$field] = $parseDate;
            } 
        }
        return $valParams;
    }

    public static function defaultPickerDate()
    {
        $currentDate = new DateTime();
        $targetDate = clone $currentDate;
        $targetDate->modify('-6 months');
        $targetDate->modify('-1 day');
        return date($targetDate->format('d/m/Y')) . '-' . date($currentDate->format('d/m/Y'));
    }

}
