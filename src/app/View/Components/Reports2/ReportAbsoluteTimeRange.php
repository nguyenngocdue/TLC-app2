<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;
use Illuminate\Support\Str;


class ReportAbsoluteTimeRange extends Component
{
    use TraitReportFilter;

    protected $reportType2 = 'report2';
    public function __construct(
        private $report = null
    )
    {}

    public function render()
    {
        $rp = $this->report;
        $currentParams = $this->currentParamsReport();
        
        return view('components.reports2.report-absolute-time-range', 
        [
            'rp' => $rp,
            'entityType' => $rp->entity_type,
            'reportType2' => $this->reportType2,
            'routeFilter' => route('report_filters' . '.update', $rp->id),
            'fromDate' => $currentParams['from_date'],
            'toDate' => $currentParams['to_date'],
        ]
    );
    }
}
