<?php

namespace App\Http\Controllers\Reports;

trait TraitGenerateTableColumns
{

    function generateTableColumns($dataSource)
    {
        $firstItem = (array)$dataSource->first();
        if (!$firstItem) return [[]];
        $tableColumns = [];
        foreach (array_keys($firstItem) as $field) {
            $tableColumns[] = [
                'dataIndex' => $field,
                'align' => 'center',
            ];
        }
        return $tableColumns;
    }
}
