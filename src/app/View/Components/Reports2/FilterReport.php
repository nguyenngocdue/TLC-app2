<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class FilterReport extends Component
{
    protected $type = "filter_report";
    use TraitFilterReport;
    public function __construct(
        private $report = "",
    ) {
    }

    public function render()
    {
        $rp = (object)$this->report;
        $rpId = $rp->id;
        $rpFilters = $rp->getRpFilters->sortBy('order_no');
        $filterLinkDetails = $rp->getFilterLinkDetails;

        $currentParams = $this->currentParamsReport();
            
        $paramsError = $this->validateParams($rpFilters, $currentParams);
        
        return view('components.reports2.filter-report', [
            'entityType' => $rp->entity_type,
            'entityType2' =>  $this->entityType2,
            'reportName' => $rp->name,
            'rpId' => $rpId,
            'currentParams' => $currentParams,
            'routeFilter' => route(Str::plural($this->type).'.update', $rp->id),
            'filterLinkDetails' => $filterLinkDetails,
            'rpFilters' => $rpFilters,
            'paramsError' => $paramsError,
        ]);
    }
}
