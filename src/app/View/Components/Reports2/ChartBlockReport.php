<?php

namespace App\View\Components\Reports2;


use Illuminate\View\Component;

class ChartBlockReport extends Component
{
    use TraitDataColumnReport;
    public function __construct(
        private $block = null,
    ) {
    }

    public function render()
    {
        $block = $this->block;
        return view('components.reports2.chart-block-report', [
            'name' => $block->name,
            'description' => $block->description,
        ]);
    }
}
