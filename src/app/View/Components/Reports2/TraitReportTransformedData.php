<?php

namespace App\View\Components\Reports2;


trait TraitReportTransformedData
{
    use TraitReportMatrixColumn;
    
    private function sortData($transformedOpt) {
        if (!$transformedOpt) return [];
        if (is_string($transformedOpt)) $transformedOpt = json_decode($transformedOpt, true);
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
                    [$transformedData, $transformedFields] = $this->createMatrix($dataSource, $params, $customCols);
                    return [collect($transformedData), $transformedFields];
                case "reduce":
                    // $params = $item['params'];


                case "filter":
                default:
                    return [$dataSource, []];
            }
        }
    }

}
