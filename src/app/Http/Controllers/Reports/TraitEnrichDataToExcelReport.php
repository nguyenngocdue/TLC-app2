<?php

namespace App\Http\Controllers\Reports;

trait TraitEnrichDataToExcelReport
{
    protected function modifyDataToExportCSV($dataSource, $modeParams)
    {
        $dataSource = $this->enrichDataSource($dataSource, $modeParams);
        $data = array_map(function ($item) {
            foreach ($item as $key => $val) {
                if (is_object($val)) {
                    $item[$key] =  !str_contains($val->value, '</') ? $val->value : "";
                }
            }
            return $item;
        }, $dataSource->toArray());
        return collect($data);
    }
}
