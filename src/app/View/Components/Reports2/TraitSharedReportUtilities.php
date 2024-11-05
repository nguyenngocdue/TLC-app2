<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use DateTime;

trait TraitSharedReportUtilities
{
    protected $DATETIME_FORMAT = 'y-m-d H:i:s';

    protected function formatDateWithTimezone($date, $timeZoneNumber, $dateDisplayFormat)
    {
        $date = DateReport::convertToTimezone($date, $timeZoneNumber);
        $date = new DateTime($date);
        return $date->format($dateDisplayFormat);
    }

    public function formatFromAndToDate($currentParams, $formatType = '')
    {
        $timeZoneNum = CurrentUser::get()->time_zone;
        $dateFormat = $formatType ? $formatType : ($currentParams['date_display_format'] ?? $this->DATETIME_FORMAT);
        if ($dateFormat && isset($currentParams['from_date'])) {
            $currentParams['from_date'] = $this->formatDateWithTimezone($currentParams['from_date'], $timeZoneNum, $dateFormat);
        }
        if ($dateFormat && isset($currentParams['to_date'])) {
            $currentParams['to_date'] = $this->formatDateWithTimezone($currentParams['to_date'], $timeZoneNum, $dateFormat);
        }
        return $currentParams;
    }
}
