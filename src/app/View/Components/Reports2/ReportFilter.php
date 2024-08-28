<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class ReportFilter extends Component
{
    use TraitReportFilter;
    protected $type = "report_filter";
    public function __construct(
        private $report = null,
    ) {}

    public function render()
    {
        $rp = (object)$this->report;
        $rpId = $rp->id;
        $rpFilters = $rp->getRpFilters->sortBy('order_no');
        $rpFilterLinks = $rp->getRpFilterLinks;

        $currentParams = $this->currentParamsReport();

        $paramsError = $this->validateParams($rpFilters, $currentParams);

        return view('components.reports2.report-filter', [
            'entityType' => $rp->entity_type,
            'reportType2' =>  $this->reportType2,
            'reportName' => $rp->name,
            'rpId' => $rpId,
            'currentParams' => $currentParams,
            'routeFilter' => route(Str::plural($this->type) . '.update', $rp->id),
            'rpFilterLinks' => $rpFilterLinks,
            'rpFilters' => $rpFilters,
            'paramsError' => $paramsError,
            'rp' => $rp,
        ]);
    }
}
