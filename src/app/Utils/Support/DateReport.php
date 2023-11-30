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

    public static function basicFormatDateString($strDate, $typeFormat = 'Y-m-d'){
        $dateTime = new DateTime($strDate);
        return $dateTime->format($typeFormat);
    }

    public static function getMonthAbbreviation($month)
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        if(!is_numeric($month)) return $month;
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

    public static function separateStrPickerDate($strPickerDate){
        try {
            if(!is_string($strPickerDate)) return ['start' =>null, 'end' => null];
            $dates = explode("-", $strPickerDate);
            return  ['start' => self::formatDateString(trim($dates[0])), 'end' =>self::formatDateString(trim($dates[1]))];
        } catch (Exception $e){
            dd($e, $strPickerDate);
        }
    }
    private static function isValidDateFormat($dateString) {
        $date = DateTime::createFromFormat('Y-m-d', $dateString);
        return ($date !== false /* && !array_sum($date::getLastErrors()) */);
    }
    

    public static function createValueForParams($fields, $params)
    {
        $valParams = [];

        foreach ($fields as $field) {
            $value = '';
            if (isset($params[$field])) {
                if ($field === 'picker_date') {
                    if(self::isValidDateFormat($params[$field])) {
                        $value = $params[$field];
                    } else {
                        $value = self::separateStrPickerDate($params[$field]);
                        // dd($value);
                    }
                }elseif($field === 'only_month') {
                    if(is_array( $params[$field])){
                        $months = array_map(fn($item) => STR_PAD($item, 2,"0", STR_PAD_LEFT), $params[$field]);
                    } else{
                        $months =STR_PAD($params[$field], 2,"0", STR_PAD_LEFT);
                    }
                    $value = StringReport::arrayToJsonWithSingleQuotes2($months, true);
                }elseif($field === 'year') {
                    $value = StringReport::arrayToJsonWithSingleQuotes2($params[$field], true);
                } 
                elseif ($field === 'status') {
                    $value = StringReport::arrayToJsonWithSingleQuotes2($params[$field]);
                } elseif (is_array($params[$field])) {
                    $value = StringReport::arrayToJsonWithSingleQuotes2($params[$field]);
                }
                else {
                    $value = StringReport::arrayToJsonWithSingleQuotes2($params[$field]);
                }
            }
            $valParams[$field] = $value;
        }
        $valParams = array_merge($params, $valParams);        
        return $valParams;
    }

    public static function defaultPickerDate($targetMonth = '-6 months')
    {
        $currentDate = new DateTime();
        $targetDate = clone $currentDate;
        $targetDate->modify($targetMonth);
        $targetDate->modify('-1 day');
        return date($targetDate->format('d/m/Y')) . '-' . date($currentDate->format('d/m/Y'));
    }

    public static function getWeeksInYear($year) {
        $weeks = [];
        $date = new DateTime();
        $date->setISODate($year, 1); // Set the date to the first day of the specified year
    
        $endOfYear = new DateTime();
        $endOfYear->setISODate($year, 53); // Set the date to the last day of the specified year
        while ($date <= $endOfYear) {
            $weekNumber = $date->format('W');
            $startOfWeek = clone $date; // Clone the DateTime object to avoid modifying the original object
            $endOfWeek = clone $date->modify('+6 days'); // Modify the cloned object to get the end of the week
            
            $weeks[(int)$weekNumber] = [
                'start_date' => $startOfWeek->format('Y-m-d'),
                'end_date' => $endOfWeek->format('Y-m-d')
            ];
            // Move to the next week
            $date->modify('+1 days');
        }
        return $weeks;
    }

    public static function getHalfYearPeriods($year) {
        $firstHalfStart = strtotime($year . '-01-01');
        $firstHalfEnd = strtotime($year . '-06-30');
        $secondHalfStart = strtotime($year . '-07-01');
        $secondHalfEnd = strtotime($year . '-12-31');
        
        // Format the dates as strings in 'Y-m-d' format
        $firstHalfStartFormatted = date('Y-m-d', $firstHalfStart);
        $firstHalfEndFormatted = date('Y-m-d', $firstHalfEnd);
        $secondHalfStartFormatted = date('Y-m-d', $secondHalfStart);
        $secondHalfEndFormatted = date('Y-m-d', $secondHalfEnd);
        
        return [
            'start_half_year' => $firstHalfStartFormatted . '/' . $firstHalfEndFormatted,
            'end_half_year' => $secondHalfStartFormatted . '/' .$secondHalfEndFormatted
        ];
    }
    
    public static function getMonthsByQuarter($quarter){
        switch ($quarter) {
            case 1:
                return ['01','02','03'];
            case 2:
                return ['04','05','06'];
            case 3:
                return ['07','08','09'];
            case 4:
                return ['10','11','12'];
            default:
                return "invalid";
        }
    }

    public static function getMonthAbbreviation2($monthNumber) {
        $months = [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        if ($monthNumber >= 1 && $monthNumber <= 12) {
            return $months[$monthNumber - 1];
        } else {
            return "Invalid month number";
        }
    }

    public static function calculateQuarterTotals($array) {
        $quarters = [
            'quarter_1' => ['01', '02', '03'],
            'quarter_2' => ['04', '05', '06'],
            'quarter_3' => ['07', '08', '09'],
            'quarter_4' => ['10', '11', '12'],
        ];
    
        $result = [];
    
        foreach ($quarters as $quarter => $months) {
            $quarterTotal = 0;
            foreach ($months as $month) {
                if (isset($array[$month])) {
                    $quarterTotal += (float)$array[$month];
                }
            }
            $result[$quarter] = $quarterTotal;
        }
        return $result;
    }

    public static function monthsToQuarters($months) {
        $quarters = [
            'QTR1' => [1, 2, 3],
            'QTR2' => [4, 5, 6],
            'QTR3' => [7, 8, 9],
            'QTR4' => [10, 11, 12],
        ];

        $result = [];
        foreach ($months as $month) {
            foreach ($quarters as $quarter => $quarterMonths) {
                if (in_array($month, $quarterMonths)) {
                    $result[$quarter][] = $month;
                    break;
                }
            }
        }
        return $result;
    }

}
