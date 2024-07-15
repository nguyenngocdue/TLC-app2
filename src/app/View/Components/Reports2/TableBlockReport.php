<?php

namespace App\View\Components\Reports2;


use Illuminate\View\Component;

class TableBlockReport extends Component
{
    public function __construct(
        private $block = null,
    ) {
    }

    public function render()
    {
        $block = $this->block;
        return view('components.reports2.table-block-report', [
            'name' => $block->name,
            'description' => $block->description,
        ]);
    }
}
