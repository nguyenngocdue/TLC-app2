<?php

namespace App\Utils\Support;

use DateTime;
use DateTimeZone;

class DefaultValueReport
{
    public static function updateDefaultValueFromDateToDate($params, $rp=null) {
        $timezone =  $params['time_zone'];
        $timezoneObj = new DateTimeZone(ReportPreset::getTimezoneFromOffset($timezone));
        $toDate = new DateTime('now', $timezoneObj);
        if ($x = $rp?->default_time_range) {
            $allPresets = PresetsTimeRange::createPresets($params);
            if (isset($allPresets[$x])) {
                $presets = $allPresets[$x];
                $params['preset_title'] = Report::makeTitle($x);
            }
        } else {
            $presets = ReportPreset::getDateOfPrevious3Months(null, $toDate) ;
            $params['preset_title'] = 'The Past Three Months';
        }
        $params = array_merge($params, $presets);        
        return $params;
    }

}
