<?php

namespace App\View\Components\Reports2;


use Illuminate\View\Component;

class DescriptionBlockReport extends Component
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

        return view('components.reports2.description-block-report', [
            'name' => $block->name ?? '',
            'description' => $block->description ?? '',
            'dataSql' => $dataSql,
        ]);
    }
}
