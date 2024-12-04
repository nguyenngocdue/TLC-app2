<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class ReportDynamicPageItem extends Component
{
    use TraitReportQueriedData;

    public function __construct(
        private $line = null,
        private $block = null,
        private $report = null,
        private $currentParams = []
    ) {}


    private function getBlockDataSource($block, $line)
    {
        $line = (array)$line;
        $formattedSqlStr = $this->getSql($block->sql_string, $line);
        $queriedData = $this->getDataSQLString($formattedSqlStr);
        dd($block);
    }

    public function render()
    {
        $line = $this->line;  # the same params in filter
        $block = $this->block;
        $blockDataSource = $this->getBlockDataSource($block, $line);
        dd($blockDataSource);

        
        return view('components.reports2.report-dynamic-page-item', []);
    }
}
