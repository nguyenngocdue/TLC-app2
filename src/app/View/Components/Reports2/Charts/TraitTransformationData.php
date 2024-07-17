<?php

namespace App\View\Components\Reports2\Charts;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;

trait TraitTransformationData
{
    public function makeRowsToFields($dataQuery, $tableColumns)
    {
        // transform dataQuery to rows to fields format.
        $result = [];
        foreach ($dataQuery as $row) {
            foreach ($tableColumns as $item) {
                $dataIndex = $item['dataIndex'];
                if (isset($row->$dataIndex)) {
                    $result[$dataIndex][] = $row->$dataIndex;
                }
            }
        }
        return $result;
    }

    public function makeSeriesChart($rowsToFields, $tableColumns)
    {
        $series = [];
        foreach ($tableColumns as $item) {
            $dataIndex = $item['dataIndex'];
            $name = isset($item['title']) ? $item['title'] : $dataIndex;
            $series[] = [
                'name' => $name,
                'data' => $rowsToFields[$dataIndex] ?? [],
            ];
        }
        return $series;
    }

    public function mapDataQueryToColumns($dataQuery, $tableColumns)
    {
        $result = [];
        foreach ($tableColumns as $key => $value) {
            $dataIndex = $value['dataIndex'];
            if (isset($dataQuery[$key])) {
                $result[$dataIndex] = array_values((array)$dataQuery[$key]);
            }
        }
        return $result;
    }
}
