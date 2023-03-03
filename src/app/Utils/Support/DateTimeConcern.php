<?php

namespace App\Utils\Support;

use App\Utils\Constant;
use Carbon\Carbon;

class DateTimeConcern
{
    public static function format($value, $formatFrom, $formatTo)
    {
        if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
        return Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
    }
    public static function convertForLoadingForQuarter($value, $quarter = 'Q01')
    {
        switch (true) {
            case str_contains($value, ltrim($quarter)):
                $value = str_replace('01', 'Q01', $value);
                break;
            case str_contains($value, '04'):
                $value = str_replace('04', 'Q02', $value);
                break;
            case str_contains($value, '07'):
                $value = str_replace('07', 'Q03', $value);
                break;
            case str_contains($value, '10'):
                $value = str_replace('10', 'Q04', $value);
                break;
            default:
                # code...
                break;
        }
        return $value;
    }
    public static function convertForLoading($control, $value)
    {
        if (!$value) return null;
        try {
            switch ($control) {
                case "picker_date":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_DATE_ASIAN;
                    //Deal with old()
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_month":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_YEAR_MONTH;
                    //Deal with old()
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_week":
                case "picker_quarter":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_YEAR_MONTH;
                    $value = self::format($value, $formatFrom, $formatTo);
                    // 01/2022 -> 
                    switch (true) {
                        case str_contains($value, '01'):
                            $value = str_replace('01', 'Q01', $value);
                            break;
                        case str_contains($value, '04'):
                            $value = str_replace('04', 'Q02', $value);
                            break;
                        case str_contains($value, '07'):
                            $value = str_replace('07', 'Q03', $value);
                            break;
                        case str_contains($value, '10'):
                            $value = str_replace('10', 'Q04', $value);
                            break;
                        default:
                            break;
                    }
                    //Deal with old()
                    break;
                case "picker_year":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_YEAR;
                    //Deal with old()
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case 'picker_datetime':
                    $formatFrom = Constant::FORMAT_DATETIME_MYSQL;
                    $formatTo = Constant::FORMAT_DATETIME_ASIAN;
                    //Deal with old()
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_time":
                    $formatFrom = Constant::FORMAT_TIME_MYSQL;
                    $formatTo = Constant::FORMAT_TIME;
                    //Deal with old()
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
                    //Deal with old()
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_month":
                    $formatFrom = Constant::FORMAT_YEAR_MONTH;
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    //Deal with old()
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_week":
                case "picker_quarter":
                    $formatFrom = Constant::FORMAT_YEAR_MONTH;
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    switch (true) {
                        case str_contains($value, 'Q01'):
                            $value = str_replace('Q01', '01', $value);
                            $value = self::format($value, $formatFrom, $formatTo);
                            break;
                        case str_contains($value, 'Q02'):
                            $value = str_replace('Q02', '04', $value);
                            $value = self::format($value, $formatFrom, $formatTo);
                            break;
                        case str_contains($value, 'Q03'):
                            $value = str_replace('Q03', '07', $value);
                            $value = self::format($value, $formatFrom, $formatTo);
                            break;
                        case str_contains($value, 'Q04'):
                            $value = str_replace('Q04', '10', $value);
                            $value = self::format($value, $formatFrom, $formatTo);
                            break;
                        default:
                            break;
                    }
                    //Deal with old()
                    break;
                case "picker_year":
                    $formatFrom = Constant::FORMAT_YEAR;
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    //Deal with old()
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_datetime":
                    $formatFrom = Constant::FORMAT_DATETIME_ASIAN;
                    $formatTo = Constant::FORMAT_DATETIME_MYSQL;
                    //Deal with old()
                    $value = self::format($value, $formatFrom, $formatTo);
                    break;
                case "picker_time":
                    $formatFrom = Constant::FORMAT_TIME;
                    $formatTo = Constant::FORMAT_TIME_MYSQL;
                    //Deal with old()
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
