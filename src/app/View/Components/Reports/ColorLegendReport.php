<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class ColorLegendReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource = []
    ) {
        //
    }

    public function render()
    {
        return view('components.reports.color-legend-report', [
            'dataSource' => $this->dataSource
        ]);
    }
}
