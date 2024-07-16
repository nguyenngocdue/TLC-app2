<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQL;
use Illuminate\View\Component;

class BlockReport extends Component
{
    use TraitDataColumnReport;
    public function __construct(
        private $blockDetails = [],
        private $reportId
    ) {
    }

    public function render()
    {

        $blockDetails = $this->blockDetails;
        $blocksDataSource = [];
        $params = [
            'report_id' => $this->reportId
        ];

        foreach ($blockDetails as $item) {
            $block = $item->getBlock;
            $dataSql = $this->getDataSQLString($block, $params);
            [$tableDataSource, $tableColumns] = $this->getColumns($block, $params, $dataSql);
            $array = [
                'colSpan' => $item->col_span,
                'blocks' => $item->getBlock,
                'backgroundBlock' => $item->attachment_background->first(),
                'dataSQL' => $dataSql,
                'tableDataSource' => $tableDataSource,
                'tableColumns' => $tableColumns,
            ];
            $blocksDataSource[] = $array;
        }
        return view('components.reports2.block-report', [
            'blocksDataSource' => $blocksDataSource,
            'reportId' => $this->reportId,
        ]);
    }
}
