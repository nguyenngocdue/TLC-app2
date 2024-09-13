<?php

namespace App\Utils\Support;

use DateTime;
use DateTimeZone;
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

    public static function basicFormatDateString($strDate, $typeFormat = 'Y-m-d')
    {
        $dateTime = new DateTime($strDate);
        return $dateTime->format($typeFormat);
    }

    public static function getMonthAbbreviation($month)
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        if (!is_numeric($month)) return $month;
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

    public static function separateStrPickerDate($strPickerDate)
    {
        try {
            if (!is_string($strPickerDate)) return ['start' => null, 'end' => null];
            $dates = explode("-", $strPickerDate);
            return  ['start' => self::formatDateString(trim($dates[0])), 'end' => self::formatDateString(trim($dates[1]))];
        } catch (Exception $e) {
            dd($e, $strPickerDate);
        }
    }
    private static function isValidDateFormat($dateString)
    {
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
                    if (self::isValidDateFormat($params[$field])) {
                        $value = $params[$field];
                    } else {
                        $value = self::separateStrPickerDate($params[$field]);
                        // dd($value);
                    }
                } elseif ($field === 'only_month') {
                    if (is_array($params[$field])) {
                        $months = array_map(fn ($item) => STR_PAD($item, 2, "0", STR_PAD_LEFT), $params[$field]);
                    } else {
                        $months = STR_PAD($params[$field], 2, "0", STR_PAD_LEFT);
                    }
                    $value = StringReport::arrayToJsonWithSingleQuotes2($months, true);
                } elseif ($field === 'year') {
                    $value = StringReport::arrayToJsonWithSingleQuotes2($params[$field], true);
                } elseif ($field === 'status') {
                    $value = StringReport::arrayToJsonWithSingleQuotes2($params[$field]);
                } elseif (is_array($params[$field])) {
                    $value = StringReport::arrayToJsonWithSingleQuotes2($params[$field]);
                } else {
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

    public static function getWeeksInYear($year)
    {
        $weeks = [];
        $date = new DateTime();
        $date->setISODate($year, 1); // Set the date to the first day of the specified year

        $endOfYear = new DateTime();
        $endOfYear->setISODate($year, 53); // Set the date to the last day of the specified year
        while ($date <= $endOfYear  && $date->format('Y') <= $year) {
            $weekNumber = $date->format('W');
            $startOfWeek = clone $date; // Clone the DateTime object to avoid modifying the original object
            $endOfWeek = clone $date->modify('+6 days'); // Modify the cloned object to get the end of the week

            if ($date->format('Y') <= $year) {
                $weeks[(int)$weekNumber] = [
                    'start_date' => $startOfWeek->format('Y-m-d'),
                    'end_date' => $endOfWeek->format('Y-m-d')
                ];
                // Move to the next week
                $date->modify('+1 days');
                // dd($weeks);
            }
        }
        // dd($weeks);
        return $weeks;
    }

    public static function getHalfYearPeriods($year)
    {
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
            'end_half_year' => $secondHalfStartFormatted . '/' . $secondHalfEndFormatted
        ];
    }

    public static function getMonthsByQuarter($quarter)
    {
        switch ($quarter) {
            case 1:
                return ['01', '02', '03'];
            case 2:
                return ['04', '05', '06'];
            case 3:
                return ['07', '08', '09'];
            case 4:
                return ['10', '11', '12'];
            default:
                return "invalid";
        }
    }

    public static function getMonthAbbreviation2($monthNumber)
    {
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

    public static function calculateQuarterTotals($array)
    {
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

    public static function monthsToQuarters($months)
    {
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

    public static function convertDatesToTimestamps($dates)
    {
        return array_map(function ($date) {
            $dateTime = DateTime::createFromFormat('d/m/Y', $date);
            if ($dateTime === false) {
                throw new Exception("Invalid date format: $date");
            }
            return $dateTime->getTimestamp() * 1000;
        }, $dates);
    }

    public static function getMonthsFromHaftYear($params)
    {
        $onlyMonths = range(1, 12);
        if (Report::checkValueOfField($params, 'half_year')) {
            $haftYearStr = $params['half_year'];
            switch ($haftYearStr) {
                case 'start_half_year':
                    $onlyMonths = range(1, 6);
                    break;
                case 'end_half_year':
                    $onlyMonths = range(6, 12);
                    break;
            }
        }
        return $onlyMonths;
    }
    public static function generateTimeRanges($years)
    {
        $result = [];

        foreach ($years as $year) {
            $shortYear = substr($year, -2); // Lấy hai chữ số cuối của năm

            $result["first_range_{$year}"] = [
                "text" => "Jan - Jun {$shortYear}",
                "begin_date" => "{$year}-01-01",
                "end_date" => "{$year}-06-30"
            ];

            $result["second_range_{$year}"] = [
                "text" => "Jul - Dec {$shortYear}",
                "begin_date" => "{$year}-07-01",
                "end_date" => "{$year}-12-31"
            ];
        }

        return $result;
    }
    public static function convertOffsetToNumber($utcOffset) {
        // Remove the '+' or '-' sign and split the hours and minutes
        $sign = substr($utcOffset, 0, 1);
        $parts = explode(':', substr($utcOffset, 1));
    
        $hours = (int) $parts[0];
        $minutes = (int) $parts[1];
    
        // Convert minutes to a fraction of an hour
        $decimalMinutes = $minutes / 60;
    
        // Combine hours and fractional hours
        $timeAsNumber = $hours + $decimalMinutes;
    
        // Apply the sign to the final number
        if ($sign === '-') {
            $timeAsNumber *= -1;
        }
        return $timeAsNumber;
    }
    
    public static function getTimeZones(){
        $timezones = [
            "UTC-12" => ["utc_offset" => "-12:00", "timezone" => "UTC-12"],
            "UTC-11" => ["utc_offset" => "-11:00", "timezone" => "UTC-11"],
            "UTC-10" => ["utc_offset" => "-10:00", "timezone" => "UTC-10"],
            "UTC-9"  => ["utc_offset" => "-09:00", "timezone" => "UTC-9"],
            "UTC-8"  => ["utc_offset" => "-08:00", "timezone" => "UTC-8"],
            "UTC-7"  => ["utc_offset" => "-07:00", "timezone" => "UTC-7"],
            "UTC-6"  => ["utc_offset" => "-06:00", "timezone" => "UTC-6"],
            "UTC-5"  => ["utc_offset" => "-05:00", "timezone" => "UTC-5"],
            "UTC-4"  => ["utc_offset" => "-04:00", "timezone" => "UTC-4"],
            "UTC-3"  => ["utc_offset" => "-03:00", "timezone" => "UTC-3"],
            "UTC-2"  => ["utc_offset" => "-02:00", "timezone" => "UTC-2"],
            "UTC-1"  => ["utc_offset" => "-01:00", "timezone" => "UTC-1"],
            "UTC+0"  => ["utc_offset" => "+00:00", "timezone" => "UTC+0"],
            "UTC+1"  => ["utc_offset" => "+01:00", "timezone" => "UTC+1"],
            "UTC+2"  => ["utc_offset" => "+02:00", "timezone" => "UTC+2"],
            "UTC+3"  => ["utc_offset" => "+03:00", "timezone" => "UTC+3"],
            "UTC+4"  => ["utc_offset" => "+04:00", "timezone" => "UTC+4"],
            "UTC+5"  => ["utc_offset" => "+05:00", "timezone" => "UTC+5"],
            "UTC+6"  => ["utc_offset" => "+06:00", "timezone" => "UTC+6"],
            "UTC+7"  => ["utc_offset" => "+07:00", "timezone" => "UTC+7"],
            "UTC+8"  => ["utc_offset" => "+08:00", "timezone" => "UTC+8"],
            "UTC+9"  => ["utc_offset" => "+09:00", "timezone" => "UTC+9"],
            "UTC+10" => ["utc_offset" => "+10:00", "timezone" => "UTC+10"],
            "UTC+11" => ["utc_offset" => "+11:00", "timezone" => "UTC+11"],
            "UTC+12" => ["utc_offset" => "+12:00", "timezone" => "UTC+12"],
            "UTC+13" => ["utc_offset" => "+13:00", "timezone" => "UTC+13"],
            "UTC+14" => ["utc_offset" => "+14:00", "timezone" => "UTC+14"],
        ];
        return $timezones;
         
    }

    public static function getUtcOffset($timezone) {
        $timezones = self::getTimeZones();
        $timezoneData = $timezones[$timezone];
        $utcOffset = $timezoneData['utc_offset'];
        $timeAsNumber = DateReport::convertOffsetToNumber($utcOffset);
        return $timeAsNumber;
    }

    public static function convertToTimezone($dateString, $utcOffset) {
        $type = 'd-m-Y H:i:s';
        $date = DateTime::createFromFormat($type, $dateString);
        if(!$date) {
            $type = 'Y-m-d H:i:s';
            $date = DateTime::createFromFormat($type, $dateString);
        } 
        if (!$date) return "Invalid date format";
        $timezoneString = sprintf("GMT%+d", $utcOffset);
        $targetTimezone = new DateTimeZone($timezoneString);
        $date->setTimezone($targetTimezone);
        return $date->format($type);
    }
}