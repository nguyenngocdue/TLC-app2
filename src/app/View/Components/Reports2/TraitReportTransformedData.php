<?php

namespace App\View\Components\Reports2;


trait TraitReportTransformedData
{
    private function sortData($transformedOpt) {
        if (is_string($transformedOpt)) $transformedOpt = json_decode($transformedOpt, true);
        uasort($transformedOpt, function($a, $b) {return $a['order_no'] <=> $b['order_no'];});
        return $transformedOpt;
    }

    public function transformData($dataSource, $transformedOpt){
        $transformedOpt = $this->sortData($transformedOpt);
        foreach($transformedOpt as $type => $item) {
            switch ($type) {
                case 'grouping_to_matrix':
                    $params = $item['params'];
                    $hasTotalCols = $item['has_total_columns'] ?? null;
                    $transformedData = $this->createMatrix($dataSource, $params, $hasTotalCols);
                    return collect($transformedData);
                case "reduce":
                    $params = $item['params'];

                case "filter":
                default:
                    return $dataSource;
            }
        }
    }

}
