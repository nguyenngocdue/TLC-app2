<?php

namespace App\Utils\Support;

use App\Models\User;
use App\Models\Workplace;
use App\Utils\Constant;
use Carbon\Carbon;
use DateInterval;
use DateTime;

class DateTimeConcern
{
    public static function getTz()
    {
        return CurrentUser::get()->timezone ??  7;
    }

    public static function formatNoTimezone($value, $formatFrom, $formatTo)
    {
        //Deal with old()
        if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        // dump($value);
        if ($formatFrom == Constant::FORMAT_MONTH) {
            $value = '01/' . $value; //<< This will prevent 29/02, 30/02, 31/02 -> 01/03, 02/03, 03/03
            $value0 = Carbon::createFromFormat(Constant::FORMAT_DATE_ASIAN, $value);
        } else {
            $value0 = Carbon::createFromFormat($formatFrom, $value);
        }
        // dump($value0->format('Y-m-d'));
        // if ($formatFrom == Constant::FORMAT_MONTH) {
        //     $value0->day = 1;
        // }
        return $value0->format($formatTo);
    }
    public static function formatForLoading($value, $formatFrom, $formatTo)
    {
        //Deal with old()
        if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        return Carbon::createFromFormat($formatFrom, $value)->setTimezone(static::getTz())->format($formatTo);
    }

    public static function formatForSaving($value, $formatFrom, $formatTo)
    {
        //Deal with old()
        if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        return Carbon::createFromFormat($formatFrom, $value)->setTimezone(-static::getTz())->format($formatTo);
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
        if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        $value = substr($value, 1);
        [$quarter, $year] = explode('/', $value);
        $result = Carbon::createFromDate($year, (($quarter - 1) * 3) + 1, 1)->startOfQuarter();
        return $result->format($formatTo);
    }
    public static function formatWeekForSaving($value, $formatTo)
    {
        if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        $value = substr($value, 1);
        [$week, $year] = explode('/', $value);
        $result = Carbon::parse("{$year}-W{$week}-1")->startOfWeek();
        return $result->format($formatTo);
    }
    public static function getWeekOfYear($timestamp)
    {
        return Carbon::parse($timestamp)->weekOfYear;
    }
    public static function formatWeekYear($start, $yearStart, $end, $yearEnd)
    {
        return 'W' . $start . '/' . $yearStart . ' - ' . 'W' . $end . '/' . $yearEnd;
    }
    public static function convertForLoading($control, $value)
    {
        if (!$value) return null;
        try {
            switch ($control) {
                case "picker_date":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_DATE_ASIAN;
                    $value = self::formatNoTimezone($value, $formatFrom, $formatTo);
                    break;
                case "picker_month":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_MONTH;
                    $value = self::formatNoTimezone($value, $formatFrom, $formatTo);
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
                    $value = self::formatNoTimezone($value, $formatFrom, $formatTo);
                    break;
                case 'picker_datetime':
                    $formatFrom = Constant::FORMAT_DATETIME_MYSQL;
                    $formatTo = Constant::FORMAT_DATETIME_ASIAN;
                    $value = self::formatForLoading($value, $formatFrom, $formatTo);
                    break;
                case "picker_time":
                    $formatFrom = Constant::FORMAT_TIME_MYSQL;
                    $formatTo = Constant::FORMAT_TIME;
                    $value = self::formatNoTimezone($value, $formatFrom, $formatTo);
                    break;
            }
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            dump("$control with value [$value] is not a valid format [$formatTo] (during Loading)");
        }
        return $value;
    }
    public static function convertForSaving($control, $value)
    {
        $formatFrom = '';
        if (!$value) return null;
        try {
            switch ($control) {
                case "picker_date":
                    $formatFrom = Constant::FORMAT_DATE_ASIAN;
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    $value = self::formatNoTimezone($value, $formatFrom, $formatTo);
                    break;
                case "picker_month":
                    $formatFrom = Constant::FORMAT_MONTH;
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    $value = self::formatNoTimezone($value, $formatFrom, $formatTo);
                    break;
                case "picker_week":
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    // dd($formatTo);
                    $value = self::formatWeekForSaving($value, $formatTo);
                    break;
                case "picker_quarter":
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    $value = self::formatQuarterForSaving($value, $formatTo);
                    break;
                case "picker_year":
                    $formatFrom = Constant::FORMAT_YEAR;
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    $value = self::formatNoTimezone($value, $formatFrom, $formatTo);
                    break;
                case "picker_datetime":
                    $formatFrom = Constant::FORMAT_DATETIME_ASIAN;
                    $formatTo = Constant::FORMAT_DATETIME_MYSQL;
                    $value = self::formatForSaving($value, $formatFrom, $formatTo);
                    break;
                case "picker_time":
                    $formatFrom = Constant::FORMAT_TIME;
                    $formatTo = Constant::FORMAT_TIME_MYSQL;
                    $value = self::formatNoTimezone($value, $formatFrom, $formatTo);
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
        // // dump("$year0 - $month0 - $date0        $begin - $end");
        // $date00 = substr($date, 8, 2);
        // $ending_date = config("hr.month_ending_date");
        // $starting_date = config("hr.month_starting_date");

        // if ($date00 <= $ending_date) {
        //     $previousMonth = Carbon::createFromDate($date)->subMonth()->format(Constant::FORMAT_DATE_MYSQL);
        //     $begin = substr($previousMonth, 0, 8) . $starting_date;
        //     $end = substr($date, 0, 8) . $ending_date;
        // } else {
        //     $nextMonth = Carbon::createFromDate($date)->addMonth()->format(Constant::FORMAT_DATE_MYSQL);
        //     $begin = substr($date, 0, 8) . $starting_date;
        //     $end = substr($nextMonth, 0, 8) . $ending_date;
        // }
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

    public static function formatTimeForPH($item)
    {
        $standardStartTime = Workplace::findFromCache($item->workplace_id)->standard_start_time;
        return $item->ph_date . ' ' . $standardStartTime;
    }
    public static function formatTimestampFromDBtoJSForPH($item)
    {
        $timestamp = static::formatTimeForPH($item);
        return static::formatTimestampFromDBtoJS($timestamp);
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
    public static function formatTimestampStartForMorning($timestamp, $userId)
    {
        $dateTime = new DateTime($timestamp);
        $timeStart = self::getStandardStartTimeMorningOfUser($userId);
        $isDifferentDay = self::isDifferentDayOfUser($userId);
        $explodeTimeStart = explode(":", $timeStart);
        $dateTime->setTime($explodeTimeStart[0], $explodeTimeStart[1], $explodeTimeStart[2]);
        if ($isDifferentDay) {
            $dateTime->sub(new DateInterval('P1D'));
        }
        return $dateTime->format("Y-m-d H:i:s");
    }
    /**
     * Convert timestamps from javascript format (Full Calendar) to database format
     *
     * @param mixed $timestamp
     * @return string
     */
    public static function formatTimestampStartForAfternoon($timestamp, $userId)
    {
        $dateTime = new DateTime($timestamp);
        $timeStart = self::getStandardStartTimeAfternoonOfUser($userId);
        $explodeTimeStart = explode(":", $timeStart);
        $dateTime->setTime($explodeTimeStart[0], $explodeTimeStart[1], $explodeTimeStart[2]);
        return $dateTime->format("Y-m-d H:i:s");
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
    public static function calcEndTime($startTime, $duration)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = $startDateTime->addMinute($duration);
        return self::formatTimestampFromDBtoJS($endDateTime->format('Y-m-d H:i:s'));
    }

    public static function calcEndTimeForPH($item)
    {
        $startTime = static::formatTimeForPH($item);
        return static::calcEndTime($startTime, ($item->ph_hours * 60 + 60));
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

    public static function setTime($timeType, $startTime, $userId)
    {
        // dd($startTime);
        switch ($timeType) {
            case 'morning':
                return self::formatTimestampStartForMorning($startTime, $userId);
            case 'afternoon':
                return self::formatTimestampStartForAfternoon($startTime, $userId);
            default:
                return self::formatTimestampFromJStoDB($startTime);
        }
    }
    public static function setDuration($timeType, $userId)
    {
        switch ($timeType) {
            case 'morning':
                return self::getDurationMorningOfUser($userId);
            case 'afternoon':
                return self::getDurationAfternoonOfUser($userId);
            default:
                return 60;
        }
    }
    public static function getStandardStartTimeMorningOfUser($id)
    {
        return User::findFromCache($id)->getWorkplace->standard_start_time;
    }
    public static function isDifferentDayOfUser($id)
    {
        return User::findFromCache($id)->getWorkplace->isDifferentDay();
    }
    public static function getStandardStartTimeAfternoonOfUser($id)
    {
        return User::findFromCache($id)->getWorkplace->getStandardStartTimeAfternoon();
    }
    public static function getDurationMorningOfUser($id)
    {
        return User::findFromCache($id)->getWorkplace->getDurationMorning();
    }
    public static function getDurationAfternoonOfUser($id)
    {
        return User::findFromCache($id)->getWorkplace->getDurationAfternoon();
    }

    public static function getDayHiddenForDayIndexWeek($start, $end = 0)
    {
        $value = [1, 2, 3, 4, 5, 6, 0];
        if ($end == 0) {
            $input = range($start, 7);
            $hasValue7 = array_search(7, $input);
            if ($hasValue7) {
                $input[$hasValue7] = 0;
            }
        } else {
            $input = range($start, $end);
        }
        $result = array_diff($value, $input);
        return array_values($result);
    }
}
