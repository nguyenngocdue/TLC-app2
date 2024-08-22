<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowReport;

trait TraitReportFilter
{
    use TraitEntityCRUDShowReport;

    function currentParamsReport()
    {
        $rp = $this->report;
        $reportId = $rp->id;
        $rpFilters = $rp->getRpFilters->sortBy('order_no');
        $rpFilterLinks = $rp->getRpFilterLinks;

        $reportType2 = $this->reportType2;
        $entityType = $rp->entity_type;
        $ins = InitUserSettingReport2::getInstance($reportType2);
        $currentParams = $ins->initParamsUserSettingRp($reportId, $entityType, $rpFilterLinks, $rpFilters);
        return $currentParams;
    }

    function validateParams($rpFilters, $currentParams)
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
}
