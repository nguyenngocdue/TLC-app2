<?php

namespace App\View\Components\Reports2;


use Illuminate\View\Component;

class ParagraphBlockReport extends Component
{
    use TraitDataColumnReport;
    public function __construct(
        private $block = null,
    ) {
    }

    public function render()
    {
        $block = $this->block;
        $dataSql = $this->getDataSQLString($block);

        return view('components.reports2.paragraph-block-report', [
            'name' => $block->name ?? '',
            'description' => $block->description ?? '',
            'dataSql' => $dataSql,
        ]);
    }
}
