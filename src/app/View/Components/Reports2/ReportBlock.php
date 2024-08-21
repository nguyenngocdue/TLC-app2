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
        private $report,
        private $blockDetails = [],
    ) {
        $this->entity_type = $this->report->entity_type;
    }

    public function render()
    {
        $blockDetails = $this->blockDetails;
        $blockDataSource = [];
        $currentPrams = $this->currentParamsReport();

        foreach ($blockDetails as $item) {
            $block = $item->getBlock;
            try {
                $dataQuery = $this->getDataSQLString($block, $currentPrams);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
            //Show sql's error
            // if (method_exists($dataQuery, "getMessage")) {
            //     dd('<p>' . $dataQuery->getMessage());
            //     return;
            // };            
            //tim cach kiem tra $dataQuery->toArray() ma khong dung ham toArray
            [$tableDataSource, $rawTableColumns, $dataHeader] =  empty($dataQuery->toArray()) ? [[], [], []] : $this->getKKKColumns($block, $currentPrams, $dataQuery);
            // set columns where `dataQuery` were empty.


            //Tofix: Check if this is necessary
            // if (empty($rawTableColumns)) {
            //     $columnInstance = ColumnReport::getInstance($block);
            //     $rawTableColumns = $columnInstance->defaultColumnsOnEmptyQuery($block);
            // }

            $blockItem = [
                'colSpan' => $item->col_span,
                'block' => $item->getBlock,
                'backgroundBlock' => $item->attachment_background->first(),
                'dataQuery' => $dataQuery,
                'tableDataSource' => $tableDataSource,
                'rawTableColumns' => $rawTableColumns,
                'dataHeader' => $dataHeader,
            ];
            $blockDataSource[] = $blockItem;
        }
        return view('components.reports2.report-block', [
            'blockDataSource' => $blockDataSource,
            'reportId' => $this->report->id,
        ]);
    }
}
