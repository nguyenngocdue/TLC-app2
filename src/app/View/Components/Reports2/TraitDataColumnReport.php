<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait TraitDataColumnReport
{
    use TraitCreateSQLReport2;

    public function getDataSQLString($block, $params)
    {
        $sqlString = $block->sql_string;
        if ($sqlString) {
            $sql = $this->getSql($sqlString, $params);
            if (is_null($sql) || !$sql) return collect();
            $sqlData = DB::select($sql);
            $collection = collect($sqlData);
            return $collection;
        }
        return collect();
    }

    public function getAllUniqueFields($collection)
    {
        return $collection->map(function ($item) {
            return array_keys(get_object_vars($item));
        })->reduce(function ($carry, $keys) {
            return $carry === null ? $keys : array_unique(array_merge($carry, $keys));
        }, null);
    }

    public function getColumns($block, $params, $dataSqlColl = [])
    {
        if (empty($dataSqlColl)) {
            $dataSqlColl = $this->getDataSQLString($block, $params);
        }
        $uniqueFields = $this->getAllUniqueFields($dataSqlColl);
        $lines = $block->getLines()->get();

        $columns = [];
        $processedIndices = []; // Renamed from 'temp' for clarity

        foreach ($lines as $line) {
            $dataIndex = $line->data_index;
            $isActive = $line->is_active;
            if ($isActive && !in_array($dataIndex, $processedIndices) && in_array($dataIndex, $uniqueFields)) {
                $processedIndices[] = $dataIndex;
                $columns[] = [
                    'title' => $line->name,
                    'dataIndex' => $dataIndex,
                    'width' => $line->width,
                ];
            }
        }
        if (empty($columns)) $columns = [['dataIndex' => null]];
        return [$dataSqlColl, $columns];
    }
}
