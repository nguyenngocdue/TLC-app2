<?php

namespace App\View\Components\Reports2;


trait TraitReportFilter
{
    use TraitSharedReportUtilities;
    public function currentParamsReport($rp) // not using timezone
    {
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

}
