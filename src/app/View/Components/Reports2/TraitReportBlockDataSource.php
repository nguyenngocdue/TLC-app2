<?php

namespace App\View\Components\Reports2;

use Illuminate\Support\Facades\Blade;

trait TraitReportBlockDataSource
{
    function getBlockDataSource($blockDetails, $currentParams){
        $blockDataSource = [];
        $queriedPagData = $queriedData = collect();
        $formattedSqlStr = '';
        $perPage = $currentParams['per_page'] ?? 10;
        
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
                    $message = $e->getMessage();
                    $blockHref = route('rp_blocks.edit', $block->id);
                    echo Blade::render(
                        '<x-feedback.alert-sql-string-error message="{{$message}}" btnHref="{{$blockHref}}" />',
                        [
                            'message' => $message,
                            'blockHref' => $blockHref,
                        ]
                    );
                }
            }
            $rpTableCols = ReportTableColumn::getInstance();
            [$headerCols, $secondHeaderCols] = $rpTableCols->getColData($block, $queriedData, $transformedFields, $currentParams);

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
            $blockDataSource[$block->id] = $blockItem;
        }
        return $blockDataSource;
    }
  
}
