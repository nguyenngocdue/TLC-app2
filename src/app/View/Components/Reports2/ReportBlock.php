<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowReport;
use Illuminate\Pagination\LengthAwarePaginator;
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

    private function paginateDataSource($dataSource, $hasPagination, $pageLimit)
    {
        $page = $_GET['page'] ?? 1;
        if ($hasPagination) {
            $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $pageLimit), $dataSource->count(), $pageLimit, $page));
        }
        return $dataSource;
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
                $queriedData = $this->paginateDataSource($queriedData, $block->has_pagination, 10);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
            [$headerCols, $secondHeaderCols] = $this->getDataColumns($block, $queriedData);


            $blockItem = [
                'colSpan' => $item->col_span,
                'block' => $block,
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
