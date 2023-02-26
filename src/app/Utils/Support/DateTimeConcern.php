<?php

namespace App\Utils\Support;

use App\Utils\Constant;
use Carbon\Carbon;

class DateTimeConcern
{
    public static function convertForLoading($control, $value)
    {
        if (!$value) return null;
        try {
            switch ($control) {
                case "picker_date":
                case "picker_month":
                case "picker_week":
                case "picker_quarter":
                case "picker_year":
                    $formatFrom = Constant::FORMAT_DATE_MYSQL;
                    $formatTo = Constant::FORMAT_DATE_ASIAN;
                    //Deal with old()
                    if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
                    $value = Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
                    break;
                case 'picker_datetime':
                    $formatFrom = Constant::FORMAT_DATETIME_MYSQL;
                    $formatTo = Constant::FORMAT_DATETIME_ASIAN;
                    //Deal with old()
                    if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
                    $value = Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
                    break;
                case "picker_time":
                    $formatFrom = Constant::FORMAT_TIME_MYSQL;
                    $formatTo = Constant::FORMAT_TIME;
                    //Deal with old()
                    if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
                    $value =  Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
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
                case "picker_month":
                case "picker_week":
                case "picker_quarter":
                case "picker_year":
                    $formatFrom = Constant::FORMAT_DATE_ASIAN;
                    $formatTo = Constant::FORMAT_DATE_MYSQL;
                    //Deal with old()
                    if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
                    $value =  Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
                    break;
                case "picker_datetime":
                    $formatFrom = Constant::FORMAT_DATETIME_ASIAN;
                    $formatTo = Constant::FORMAT_DATETIME_MYSQL;
                    //Deal with old()
                    if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
                    $value =  Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
                    break;
                case "picker_time":
                    $formatFrom = Constant::FORMAT_TIME;
                    $formatTo = Constant::FORMAT_TIME_MYSQL;
                    //Deal with old()
                    if (\DateTime::createFromFormat($formatTo, $value) !== false) return $value;
                    $value =  Carbon::createFromFormat($formatFrom, $value)->format($formatTo);
                    break;
            }
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            dump("$control with value [$value] is not a valid format [$formatFrom] (during Saving)");
            // dd();
        }
        return $value;
    }
}
