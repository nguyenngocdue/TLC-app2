<?php

namespace App\View\Components\Reports2;


use Illuminate\View\Component;

class ChartBlockReport extends Component
{
    use TraitDataColumnReport;
    public function __construct(
        private $block = null,
        private $dataQuery = null,
    ) {
    }

    public function render()
    {
        $block = $this->block;
        return view('components.reports2.chart-block-report', [
            'name' => $block->name,
            'description' => $block->description,
            'chartType' => $block->chart_type,
            'chartJson' => $block->chart_json,
            'dataQuery' => $this->dataQuery,
        ]);
    }
}
