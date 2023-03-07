<?php

namespace App\Utils\Support;

use App\Utils\Constant;
use Carbon\Carbon;

class DateTimeConcern
{
    public static function format($value, $formatFrom, $formatTo)
    {
        //Deal with old()
        if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        return Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
    }
    public static function format2($value, $formatFrom)
    {
        return Carbon::createFromFormat($formatFrom, $value);
    }
    public static function formatQuarterForLoading($value, $formatFrom, $formatTo)
    {
        //Deal with old()
        if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        $result = Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
        $quarter = Carbon::createFromFormat($formatFrom, $value)->quarter;
        return str_replace('q', $quarter, $result);
    }
    public static function formatWeekForLoading($value, $formatFrom, $formatTo)
    {
        //Deal with old()
        if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
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
}
