<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowReport;
use Illuminate\View\Component;

class BlockReport extends Component
{
    use TraitDataColumnReport;
    use TraitFilterReport;
    public function __construct(
        private $blockDetails = [],
        private $report
    ) {
        $this->entity_type = $this->report->entity_type;
    }

    public function render()
    {
        $blockDetails = $this->blockDetails;
        $blocksDataSource = [];
        $currentPrams = $this->currentParamsReport();
        // dd($currentPrams);
        
        foreach ($blockDetails as $item) {
            $block = $item->getBlock;
            $dataQuery = $this->getDataSQLString($block, $currentPrams);
            [$tableDataSource, $rawTableColumns, $dataHeader] =  empty($dataQuery->toArray()) ? [[],[],[]] : $this->getColumns($block, $currentPrams, $dataQuery);
            $array = [
                'colSpan' => $item->col_span,
                'blocks' => $item->getBlock,
                'backgroundBlock' => $item->attachment_background->first(),
                'dataQuery' => $dataQuery,
                'tableDataSource' => $tableDataSource,
                'rawTableColumns' => $rawTableColumns,
                'dataHeader' => $dataHeader,
            ];
            $blocksDataSource[] = $array;
        }
        return view('components.reports2.block-report', [
            'blocksDataSource' => $blocksDataSource,
            'reportId' => $this->report->id,
        ]);
    }
}
