<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\DateFormat;

trait TraitReportTransformedData
{
    use TraitReportMatrixColumn;
    use TraitReportTableContent;
    
    private function sortData($strJson) {
        if (!$strJson) return [];
        $transformedOpt = json_decode($strJson, true);
        if(is_null($transformedOpt)) dd($strJson);
        if ($transformedOpt) {
            uasort($transformedOpt, function($a, $b) {
                if (isset($a['order_no']) && isset($b['order_no'])) {
                    return $a['order_no'] <=> $b['order_no'];
                }
            });
            return $transformedOpt;
        }
        return [];
    }

    public function transformData($dataSource, $transformedOpt){
        $transformedOpt = $this->sortData($transformedOpt);
        if (!$transformedOpt) return $dataSource;
        foreach($transformedOpt as $type => $item) {
            switch ($type) {
                case 'grouping_to_matrix':
                    $params = $item['params'];
                    $customCols = $item['custom_columns'] ?? null;
                    [$transformedData, $transformedFields] = $this->createMatrix($item, $dataSource, $params, $customCols);
                    return [collect($transformedData), $transformedFields];
                case "reduce":
                    // $params = $item['params'];
                case "filter":
                default:
                    return [$dataSource, []];
            }
        }
    }

    public function getNormalData($dataSource, $block) {
        $columns = $block->getLines;
        $colToSets = [];
        foreach ($columns as $column) {
            if ($column['is_active'] && $column['row_renderer']) {
                $colToSets[$column['data_index']] = [
                    'dataIndex' => $column['data_index'],
                    'row_renderer' => $column['row_renderer'],
                ];
            }
        }
        foreach ($dataSource as &$item) {
            foreach ($item as $key => &$value) {
                if (isset($colToSets[$key]) && $colToSets[$key]['row_renderer'] === $this->ROW_RENDERER_DATETIME_ID) {
                    $value = DateFormat::getValueDatetimeByCurrentUser($value);
                }
            }
        }
        return $dataSource;
    }
}
