<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowReport;

trait TraitFilterReport
{
    use TraitEntityCRUDShowReport;
    
    function currentParamsReport(){
        $rp = $this->report;
        $reportId = $rp->id;
        $rpFilters = $rp->getRpFilters->sortBy('order_no');
        $filterLinkDetails = $rp->getFilterLinkDetails;
        $entityType2 = $this->entityType2;
        $entityType = $rp->entity_type;
        $ins = InitUserSettingReport2::getInstance($entityType2);
        $currentParams = $ins->initParamsUserSettingRp($reportId, $entityType, $filterLinkDetails, $rpFilters);
        return $currentParams;
    }
}
