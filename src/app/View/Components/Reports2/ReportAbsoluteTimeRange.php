<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class ReportAbsoluteTimeRange extends Component
{
    public function __construct(
        private $report = null
    )
    {}

    public function render()
    {
        // dd(123);
        return view('components.reports2.report-absolute-time-range');
    }
}
