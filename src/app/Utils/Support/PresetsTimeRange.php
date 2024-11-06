<?php

namespace App\Utils\Support;

use DateTime;

class PresetsTimeRange {
    public static function createPresets($currentParams)
    {
        $timezone = $currentParams['time_zone'];
        $utcOffset = DateReport::getUtcOffset($timezone);
        $toDate = new DateTime();
        $toDate->modify("{$utcOffset} hours");

        $presets = [

            'all_the_time' => ReportPreset::getAllTime($timezone, $toDate), // from_date: 1997-08-30 00:00:00

            'today_so_far' => ReportPreset::getDateOfToday($timezone, clone $toDate),
            
            'this_week' => ReportPreset::getDateOfThisWeek($timezone),
            'this_week_so_far' => ReportPreset::getDateOfThisWeek($timezone, clone $toDate),
            
            'this_month' => ReportPreset::getDateOfThisMonth($timezone),
            'this_month_so_far' =>  ReportPreset::getDateOfThisMonth($timezone, clone $toDate),
            'the_past_three_months' =>  ReportPreset::getDateOfPrevious3Months($timezone, clone $toDate),

            'this_year' => ReportPreset::getDateOfThisYear($timezone),
            'this_year_so_far' => ReportPreset::getDateOfThisYear($timezone, clone $toDate),

            'first_half_year' => ReportPreset::getDateOfHalfYear('first_half', $timezone),
            'second_half_year' => ReportPreset::getDateOfHalfYear('second_half', $timezone),

            'this_quarter' => ReportPreset::getDateThisQuarter($timezone),
            'this_quarter_so_far' => ReportPreset::getDateThisQuarter($timezone, clone $toDate),

            'first_quarter' => ReportPreset::getDateForQuarter(1, $timezone),
            'second_quarter' => ReportPreset::getDateForQuarter(2, $timezone),
            'third_quarter' => ReportPreset::getDateForQuarter(3, $timezone),
            'fourth_quarter' => ReportPreset::getDateForQuarter(4, $timezone),
            

            'today' => ReportPreset::generateDateRange('today', clone $toDate, clone $toDate),
            'yesterday' => ReportPreset::generateDateRange('yesterday', clone $toDate, clone $toDate),
            'last_2_days' => ReportPreset::generateDateRange('last_2_days', clone $toDate, clone $toDate),
            'last_week' => ReportPreset::generateDateRange('last_week', clone $toDate, clone $toDate),
            'last_month' => ReportPreset::generateDateRange('last_month', clone $toDate, clone $toDate),
            'last_year' => ReportPreset::generateDateRange('last_year', clone $toDate, clone $toDate),
            'last_2_years' => ReportPreset::generateDateRange('last_2_years', clone $toDate, clone $toDate),

            'last_5_minutes' => ReportPreset::generateDateRange('last_5_minutes', clone $toDate, clone $toDate),
            'last_15_minutes' => ReportPreset::generateDateRange('last_15_minutes', clone $toDate, clone $toDate),
            'last_30_minutes' => ReportPreset::generateDateRange('last_30_minutes', clone $toDate, clone $toDate),
            'last_1_hour' => ReportPreset::generateDateRange('last_1_hour', clone $toDate, clone $toDate),
            'last_3_hours' => ReportPreset::generateDateRange('last_3_hours', clone $toDate, clone $toDate),
            'last_6_hours' => ReportPreset::generateDateRange('last_6_hours', clone $toDate, clone $toDate),
            'last_12_hours' => ReportPreset::generateDateRange('last_12_hours', clone $toDate, clone $toDate),

        ];

        return $presets;
    }
}