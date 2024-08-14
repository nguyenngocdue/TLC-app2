<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class FilterReport extends Component
{

    protected $entityType2 = 'report2';
    public $refreshPage = false;

    public function __construct(
        private $report = "",
        private $paramsUrl = [],

    ) {}

    public function render()
    {
        
        $rp = (object)$this->report;
        $reportId = $rp->id;
        $rpFilters = $rp->getRpFilters;
        $filterLinkDetails = $rp->getFilterLinkDetails;
        
        $rpName = $rp->name;
        $entityType = $rp->entity_type;
        $entityType2 = $this->entityType2;
        
        $ins = InitUserSettingReport2::getInstance($entityType2);
        if($paramsUrl = $this->paramsUrl){
            $currentParams = $ins->initParamsUrlUserSettingRp($entityType, $paramsUrl, $rpFilters);
        } else {
            $currentParams = $ins->initParamsUserSettingRp($reportId, $entityType, $filterLinkDetails, $rpFilters);
        }
        return view('components.reports2.filter-report', [
            'entityType' => $entityType,
            'reportName' => $rpName,
            'entityType2' => $entityType2,
            'entity_type' => $entityType,
            'reportId' => $reportId,
            'currentParams' => $currentParams,
            'routeFilter' => route('filter_report.update', $rp->id),
            'refreshPage' => $this->refreshPage,
            'filterLinkDetails' => $filterLinkDetails,
            'rpFilters' => $rpFilters
        ]);
    }
}
