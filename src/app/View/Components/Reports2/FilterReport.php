<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;
use Illuminate\Support\Str;

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
        
        $insInitUserSetting = InitUserSettingReport2::getInstance($entityType2);

        if($paramsUrl = $this->paramsUrl){
            $currentParams = $insInitUserSetting->initParamsUrlUserSettingRp($entityType, $paramsUrl, $rpFilters);
        } else {
            $currentParams = $insInitUserSetting->initParamsUserSettingRp($entityType, $filterLinkDetails, $rpFilters);
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


/*
    Case 1
    https://127.0.0.1:38002/dashboard/rp_reports/34?project_id=5&sub_project_id=82&prod_order_id=50&prod_routing_id=2
    Case 2
    https://127.0.0.1:38002/dashboard/rp_reports/34?sub_project_id[]=5&sub_project_id[]=6&sub_project_id[]=7
*/