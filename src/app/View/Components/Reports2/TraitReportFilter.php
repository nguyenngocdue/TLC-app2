<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowReport;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use DateTime;

trait TraitReportFilter
{
    use TraitEntityCRUDShowReport;

    protected $DATETIME_FORMAT = 'y-m-d H:i:s';

    public function currentParamsReport() // not using timezone
    {
        $rp = $this->report;
        $rpFilters = $rp->getRpFilters->sortBy('order_no');
        $rpFilterLinks = $rp->getRpFilterLinks;

        $reportType2 = $this->reportType2;
        $entityType = $rp->entity_type;
        $ins = InitUserSettingReport2::getInstance($reportType2);
        $currentParams = $ins->initParamsUserSettingRp($rp, $entityType, $rpFilterLinks, $rpFilters);
        return $currentParams;
    }

    public function validateParams($rpFilters, $currentParams)
    {
        $result = [];
        foreach ($rpFilters as $filter) {
            if ($filter->is_required) {
                $dataIndex = $filter->data_index;
                if (!isset($currentParams[$dataIndex]) || is_null($currentParams[$dataIndex])) {
                    $result[$dataIndex] = $filter->title ?: $filter->entity_type;
                }
            }
        }
        return $result;
    }

    private function formatDateWithTimezone($date, $timeZoneNumber, $dateDisplayFormat)
    {
        $date = DateReport::convertToTimezone($date, $timeZoneNumber);
        $date = new DateTime($date);
        // dump($date,  $date->format('Y-m-d H:i:s'));
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
