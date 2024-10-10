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
        $presets = ReportPreset::getDateOfPrevious3Months(null, $toDate) ;
        $params = array_merge($params, $presets);        
        $params['preset_title'] = 'The Past Three Months';
        return $params;
    }

}
