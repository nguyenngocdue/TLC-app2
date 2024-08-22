<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowReport;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ReportBlock extends Component
{
    use TraitReportDataAndColumn;
    use TraitReportFilter;
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
                $queriedData = $this->getDataSQLString($block, $currentPrams);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
            //tim cach kiem tra $queriedData->toArray() ma khong dung ham toArray
            [$headerCols, $secondHeaderCols] =  $queriedData->isEmpty() ? [[], []] : $this->getDataColumns($block, $queriedData);

            $blockItem = [
                'colSpan' => $item->col_span,
                'block' => $item->getBlock,
                'backgroundBlock' => $item->attachment_background->first(),
                'queriedData' => $queriedData,
                'tableDataSource' => $queriedData,
                'headerCols' => $headerCols,
                'secondHeaderCols' => $secondHeaderCols,
            ];
            $blockDataSource[] = $blockItem;
        }
        return view('components.reports2.report-block', [
            'blockDataSource' => $blockDataSource,
            'reportId' => $this->report->id,
        ]);
    }
}
