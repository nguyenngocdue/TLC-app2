<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Collection;

trait TraitDynamicColumnsTableReport
{
    function createTableColumns(
        $dataSource,
        $strFromField = '',
        $editDataCols = [],
        $align = 'right',
        $width = 100,
        $sort = True,
    ) {
        $dataSource = $dataSource instanceof Collection ? array_map(fn ($i) => (array)$i, $dataSource->toArray()) : $dataSource->items();
        if (empty($dataSource)) return [[]];
        $array = (array)array_slice($dataSource, 0, 1)[0];

        $datColumns = [];
        if ($strFromField) {
            $fromField =  ($x = array_search($strFromField, array_keys($array))) ? $x + 1 : 0;
            $datColumns = array_keys(array_slice($array, $fromField, count($array) - $fromField + 1));
        } else {
            $datColumns = array_keys($array);
        }
        $sort ? sort($datColumns) : $datColumns;
        $indexEditFields = array_column($editDataCols, 'dataIndex');
        // dd($datColumns);

        array_walk($datColumns, function ($colName, $key) use (&$datColumns, $align, $width, $indexEditFields, $editDataCols) {
            if ($x = array_search($colName, $indexEditFields)) {
                $datColumns[$key] = $editDataCols[$x];
            } else {
                $datColumns[$key] = [
                    'dataIndex' => $colName,
                    'align' => $align,
                    'width' => $width
                ];
            }
        });
        return $datColumns;
    }
}
