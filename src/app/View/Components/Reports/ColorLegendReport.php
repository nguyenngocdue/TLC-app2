<?php

namespace App\View\Components\Reports;

use App\Utils\Support\Report;
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
        $titleLegend = Report::replaceAndUcwords(array_key_first($this->dataSource));
        return view('components.reports.color-legend-report', [
            'titleLegend' => $titleLegend,
            'dataRender' => array_pop($this->dataSource)
        ]);
    }
}
