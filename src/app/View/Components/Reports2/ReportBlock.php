<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowReport;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ReportBlock extends Component
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

        foreach ($blockDetails as $item) {
            $block = $item->getBlock;
            $dataQuery = $this->getDataSQLString($block, $currentPrams);
            //Show sql's error
            if (method_exists($dataQuery, "getMessage")) {
                dd('<p>' . $dataQuery->getMessage());
                return;
            };
            [$tableDataSource, $rawTableColumns, $dataHeader] =  empty($dataQuery->toArray()) ? [[], [], []] : $this->getColumns($block, $currentPrams, $dataQuery);
            // set columns where `dataQuery` were empty.
            if (empty($rawTableColumns)) {
                $insCol = ColumnReport::getInstance($block);
                $rawTableColumns = $insCol->defaultColumnsOnEmptyQuery($block);
            }
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
        return view('components.reports2.report-block', [
            'blocksDataSource' => $blocksDataSource,
            'reportId' => $this->report->id,
        ]);
    }
}
