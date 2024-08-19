<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class FilterReport extends Component
{

    public $refreshPage = false;
    
    use TraitFilterReport;
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
        $rpFilters = $rp->getRpFilters->sortBy('order_no');
        $filterLinkDetails = $rp->getFilterLinkDetails;

        $currentParams = $this->currentParamsReport();
            
        $paramsWarning = $this->getParamsWarning($rpFilters, $currentParams);
        
        return view('components.reports2.filter-report', [
            'entityType' => $rp->entity_type,
            'reportName' => $rp->name,
            'entityType2' =>  $this->entityType2,
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
