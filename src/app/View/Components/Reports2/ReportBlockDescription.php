<?php

namespace App\View\Components\Reports2;


use Illuminate\View\Component;

class ReportBlockDescription extends Component
{
    use TraitDataColumnReport;
    public function __construct(
        private $block = null,
    ) {}

    public function render()
    {
        $block = $this->block;

        return view('components.reports2.report-block-description', [
            'name' => $block->name ?? '',
            'description' => $block->description ?? '',
        ]);
    }
}
