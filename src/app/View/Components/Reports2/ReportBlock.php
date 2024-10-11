<?php

namespace App\View\Components\Reports2;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class ReportBlock extends Component
{
    use TraitReportQueriedData;
    use TraitReportFilter;
    // use TraitReportCreateTableColumn;

    public function __construct(
        private $report,
        private $blockDetails = [],
    ) {
        $this->entity_type = $this->report->entity_type;
    }

    private function paginateDataSource($dataSource, $hasPagination, $perPage)
    {
        $page = $_GET['page'] ?? 1;
        if ($hasPagination) {
            $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $perPage), $dataSource->count(), $perPage, $page));
        }

        return $dataSource;
    }


    public function render()
    {
        $blockDetails = $this->blockDetails;
        $currentParams = $this->currentParamsReport();
        $perPage = $currentParams['per_page'] ?? 10;

        $blockDataSource = [];
        $queriedPagData = $queriedData = collect();
        $formattedSqlStr = '';
        foreach ($blockDetails as $item) {
            if (!$item->is_active) continue;
            $block = $item->getBlock;
            $transformedFields = [];
            $formattedSqlStr = $this->getSql($block->sql_string, $currentParams);
            if ($formattedSqlStr) {
                try {
                    $queriedData = $this->getDataSQLString($formattedSqlStr);
                    if ($block->transformed_data_string) {
                        [$queriedData, $transformedFields] = $this->getTransformedData($queriedData, $block);
                    }
                    $queriedPagData = $this->paginateDataSource($queriedData, $block->has_pagination, $perPage);
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
            $rpTableCols = ReportTableColumn::getInstance();
            [$headerCols, $secondHeaderCols] = $rpTableCols->getColData($block, $queriedData, $transformedFields);

            $blockItem = [
                'colSpan' => $item->col_span,
                'block' => $block,
                'backgroundBlock' => $item->attachment_background->first(),
                'queriedData' => $queriedData,
                'tableDataSource' => $queriedPagData,
                'headerCols' => $headerCols,
                'secondHeaderCols' => $secondHeaderCols,
                'transformedFields' => $transformedFields,
                'sqlString' => $formattedSqlStr,
            ];
            $blockDataSource[] = $blockItem;
        }

        // Update currentParams to use UTC time
        $currentFormattedParams = $this->formatFromAndToDate($currentParams);

        return view('components.reports2.report-block', [
            'blockDataSource' => $blockDataSource,
            'reportId' => $this->report->id,
            'currentParams' => $currentParams,
            'currentFormattedParams' => $currentFormattedParams,
        ]);
    }
}
