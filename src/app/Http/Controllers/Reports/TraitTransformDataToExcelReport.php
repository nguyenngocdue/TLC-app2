<?php

namespace App\Http\Controllers\Reports;

trait TraitTransformDataToExcelReport
{
    protected function modifyDataToExportCSV($dataSource, $modeParams)
    {
        $dataSource = $this->transformDataSource($dataSource, $modeParams);
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
