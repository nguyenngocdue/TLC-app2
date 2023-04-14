<?php

namespace App\Http\Controllers\Reports;

trait TraitModifyDataToExcelReport
{
    protected function modifyDataToExportCSV($dataSource)
    {
        $data = array_map(function ($item) {
            $item = (object)$item;
            foreach ($item as $key => $val) {
                if (is_object($val)) {
                    $item->$key =  !str_contains((string)$val->value, '</') ? $val->value : "";
                }
            }
            return $item;
        }, $dataSource->toArray());
        // dd($dataSource, $data);
        return collect($data);
    }
}
