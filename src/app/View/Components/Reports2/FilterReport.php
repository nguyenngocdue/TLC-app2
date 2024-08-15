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

    private function getParamsWarning($rpFilter, $currentParams)
    {
        $result = [];
        foreach ($rpFilter as $filter) {
            if ($filter->is_required) {
                $dataIndex = $filter->is_multiple ? Str::plural($filter->data_index) : $filter->data_index;
                if (!isset($currentParams[$dataIndex]) || is_null($currentParams[$dataIndex])) {
                    $result[$dataIndex] = $filter->title ? $filter->title : $filter->entity_type;
                }
            }
        }
        return $result;
    }
    

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
        $currentParams = $ins->initParamsUserSettingRp($reportId, $entityType, $filterLinkDetails, $rpFilters);
            
        $paramsWarning = $this->getParamsWarning($rpFilters, $currentParams);
        
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
            'rpFilters' => $rpFilters,
            'paramsWarning' => $paramsWarning,
        ]);
    }
}
