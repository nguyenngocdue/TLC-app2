<?php

namespace App\Utils\Support;

use DateTime;
use DateTimeZone;

class DefaultValueReport
{
    public static function updateDefaultValueFromDateToDate($params) {
        $timezone =  $params['time_zone'];
        $timezoneObj = new DateTimeZone(ReportPreset::getTimezoneFromOffset($timezone));
        $toDate = new DateTime('now', $timezoneObj);
        $presets = ReportPreset::getDateThisQuarter($timezone, $toDate);
        $params = array_merge($params, $presets);        
        $params['preset_title'] = 'Absolute Time Range';
        return $params;
    }

}
