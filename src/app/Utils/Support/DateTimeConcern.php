<?php

namespace App\Utils\Support;

use App\Utils\Constant;
use Carbon\Carbon;
use DateTime;

class DateTimeConcern
{
    public static function format($value, $formatFrom, $formatTo)
    {
        //Deal with old()
        if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        return Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
    }
    public static function formatQuarterForLoading($value, $formatFrom, $formatTo)
    {
        //Deal with old()
        // if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        $regexQuarter = '/[Qq][1-4][\/][0-9]{4}/m';
        if (preg_match($regexQuarter, $value)) return $value;
        $result = Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
        $quarter = Carbon::createFromFormat($formatFrom, $value)->quarter;
        return str_replace('q', $quarter, $result);
    }
    public static function formatWeekForLoading($value, $formatFrom, $formatTo)
    {
        //Deal with old()
        // if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        $regexWeek = '/[Ww]([0-4][0-9]|5[0-3])[\/][0-9]{4}/m';
        if (preg_match($regexWeek, $value)) return $value;
        $result = Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
        return "W" . $result;
    }
    public static function formatQuarterForSaving($value, $formatTo)
    {
        $value = substr($value, 1);
        [$quarter, $year] = explode('/', $value);
        $result = Carbon::createFromDate($year, (($quarter - 1) * 3) + 1, 1)->startOfQuarter();
        return $result->format($formatTo);
    }
    public static function formatWeekForSaving($value, $formatTo)
    {
        $value = substr($value, 1);
        [$week, $year] = explode('/', $value);
        $result = Carbon::parse("{$year}-W{$week}-1")->startOfWeek();
        return $result->format($formatTo);
    }
    public static function convertForLoading($control, $value)
    {
        if (!$value) return null;
        try {
            switch ($control) {
                case "picker_date":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_DATE_ASIAN;
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_month":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_MONTH;
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_week":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_WEEK;
                    $value = self::formatWeekForLoading($value, $formatFrom, $formatTo);
                    break;
                case "picker_quarter":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_QUARTER;
                    $value = self::formatQuarterForLoading($value, $formatFrom, $formatTo);
                    break;
                case "picker_year":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_YEAR;
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case 'picker_datetime':
                    $formatFrom = Constant::FORMAT_DATETIME_MYSQL;
                    $formatTo = Constant::FORMAT_DATETIME_ASIAN;
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_time":
                    $formatFrom = Constant::FORMAT_TIME_MYSQL;
                    $formatTo = Constant::FORMAT_TIME;
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
            }
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            dump("$control with value [$value] is not a valid format [$formatTo] (during Loading)");
        }
        return $value;
    }
    public static function convertForSaving($control, $value)
    {
        if (!$value) return null;
        try {
            switch ($control) {
                case "picker_date":
                    $formatFrom = Constant::FORMAT_DATE_ASIAN;
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_month":
                    $formatFrom = Constant::FORMAT_MONTH;
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_week":
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    $value = self::formatWeekForSaving($value, $formatTo);
                    break;
                case "picker_quarter":
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    $value = self::formatQuarterForSaving($value, $formatTo);
                    break;
                case "picker_year":
                    $formatFrom = Constant::FORMAT_YEAR;
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_datetime":
                    $formatFrom = Constant::FORMAT_DATETIME_ASIAN;
                    $formatTo = Constant::FORMAT_DATETIME_MYSQL;
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_time":
                    $formatFrom = Constant::FORMAT_TIME;
                    $formatTo = Constant::FORMAT_TIME_MYSQL;
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
            }
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            dump("$control with value [$value] is not a valid format [$formatFrom] (during Saving)");
            // dd();
        }
        return $value;
    }
    public static function format2($value, $formatFrom)
    {
        return Carbon::createFromFormat($formatFrom, $value);
    }
    public static function formatWeek2($value)
    {
        [$week, $year] = explode('/', $value);
        return Carbon::parse("{$year}-W{$week}");
    }
    public static function formatQuarter2($value)
    {
        [$quarter, $year] = explode('/', $value);
        return Carbon::createFromDate($year, (($quarter - 1) * 3) + 1);
    }

    // private static function check_in_range($start_date, $end_date, $date_from_user)
    // {
    //     // Convert to timestamp
    //     $start_ts = strtotime($start_date);
    //     $end_ts = strtotime($end_date);
    //     $user_ts = strtotime($date_from_user);

    //     // Check that user date is between start & end
    //     return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    // }

    /** If date in 26/12/2023 ++, it will return year of 2024 */
    /** Else it will return year as last 4 digits of the input date */
    //     dump(DateTimeConcern::getMonthBeginAndEndDate0('2023-01-12'));
    //     dump(DateTimeConcern::getMonthBeginAndEndDate0('2023-12-12'));
    //     dump(DateTimeConcern::getMonthBeginAndEndDate0('2023-12-25'));
    //     dump(DateTimeConcern::getMonthBeginAndEndDate0('2023-12-26'));
    //     dump(DateTimeConcern::getMonthBeginAndEndDate0('2023-12-31'));
    //     dump(DateTimeConcern::getMonthBeginAndEndDate0('2024-01-01'));
    public static function getMonthBeginAndEndDate0($date)
    {
        $year0 = substr($date, 0, 4);
        $month0 = substr($date, 5, 2);
        $date0 = substr($date, 8, 2);

        $previousMonth0 = (($month0 * 1 - 1) % 12);
        $previousMonth0 = $previousMonth0 == 0 ? 12 : $previousMonth0;
        $nextMonth0 = ($month0 * 1 + 1) % 12;

        $previousMonth0 = str_pad($previousMonth0, 2, 0, STR_PAD_LEFT);
        $nextMonth0 = str_pad($nextMonth0, 2, 0, STR_PAD_LEFT);

        $starting = config()->get('hr.month_starting_date', 26);
        $ending = config()->get('hr.month_ending_date', 25);

        if ($date0 <= $ending) {
            // echo "Smaller than ending, return previous month";
            $previousYear0 = (1 * $month0 == 1) ? $year0 - 1 : $year0;
            $begin = "$previousYear0-$previousMonth0-$starting";
            $end = "$year0-$month0-$ending";
        } else {
            // echo "Greater than ending, return next month";
            $nextYear0 = ($month0 == 12) ? $year0 + 1 : $year0;
            $begin = "$year0-$month0-$starting";
            $end = "$nextYear0-$nextMonth0-$ending";
        }
        // dump("$year0 - $month0 - $date0        $begin - $end");
        return [$begin, $end];
    }

    public static function getYearBeginAndEndDate0($date)
    {
        $year0 = substr($date, 0, 4);
        $begin = static::getMonthBeginAndEndDate0($year0 . "-01-01");
        $end = static::getMonthBeginAndEndDate0($year0 . "-12-01");
        return [$begin[0], $end[1]];
    }


    /**
     * Convert timestamps from database format to javascript format (Full Calendar)
     *
     * @param mixed $timestamp
     * @return string
     */
    public static function formatTimestampFromDBtoJS($timestamp)
    {
        return str_replace(' ', 'T', $timestamp) . 'Z';
    }

    /**
     * Convert timestamps from javascript format (Full Calendar) to database format
     *
     * @param mixed $timestamp
     * @return string
     */
    public static function formatTimestampFromJStoDB($timestamp)
    {
        $dateTime = Carbon::parse($timestamp);
        $utcDate = $dateTime->utc();
        return $utcDate->toDateTimeString();
    }
    /**
     * Convert timestamps from javascript format (Full Calendar) to database format
     *
     * @param mixed $timestamp
     * @return string
     */
    public static function formatTimestampStartForMorning($timestamp)
    {
        $dateTime = new DateTime($timestamp);
        $dateTime->setTime(8, 0, 0);
        return self::formatTimestampFromJStoDB($dateTime->format("Y-m-d\TH:i:sP"));
    }
    /**
     * Convert timestamps from javascript format (Full Calendar) to database format
     *
     * @param mixed $timestamp
     * @return string
     */
    public static function formatTimestampStartForAfternoon($timestamp)
    {
        $dateTime = new DateTime($timestamp);
        $dateTime->setTime(13, 0, 0);
        return self::formatTimestampFromJStoDB($dateTime->format("Y-m-d\TH:i:sP"));
    }
    /**
     * Calculate duration based on start time and end time  
     *
     * @param mixed $startTime
     * @param mixed $endTime
     * @return int
     */
    public static function calDurationFromStartTimeAndEndTime($startTime, $endTime)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = Carbon::parse($endTime);
        return $endDateTime->diffInMinutes($startDateTime);
    }
    /**
     * Calculate end time based on start time and duration
     *
     * @param mixed $startTime
     * @param int $duration
     * @return string
     */
    public static function calTimestampEndFromStartTimeAndDuration($startTime, $duration)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = $startDateTime->addMinute($duration);
        return self::formatTimestampFromDBtoJS($endDateTime->format('Y-m-d H:i:s'));
    }

    /**
     * Check timestamp has correct format required
     *
     * @param mixed $timestamp
     * @return boolean
     */
    public static function isFormatJsDateTime($timestamp)
    {
        return date_create_from_format("Y-m-d\TH:i:sP", $timestamp);
    }

    public static function setTime($timeType, $startTime)
    {
        switch ($timeType) {
            case 'morning':
                return self::formatTimestampStartForMorning($startTime);
            case 'afternoon':
                return self::formatTimestampStartForAfternoon($startTime);
            default:
                return self::formatTimestampFromJStoDB($startTime);
        }
    }
    public static function setDuration($timeType)
    {
        switch ($timeType) {
            case 'morning':
                return Constant::TIME_DEFAULT_MORNING;
            case 'afternoon':
                return Constant::TIME_DEFAULT_AFTERNOON;
            default:
                return 60;
        }
    }
}
