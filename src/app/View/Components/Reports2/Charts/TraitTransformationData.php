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

    public function chartJsonDeCode()
    {
        $chartJson = $this->chartJson;
        $chartJson = json_decode($chartJson);
        return array_pop($chartJson);;
    }

    public function makeSeriesChart($rowsToFields, $tableColumns)
    {
        $series = [];
        $chartTypeName = $this->chartTypeName ?? '';

        $dataIndexes = array_column($tableColumns, 'dataIndex');
        if ($chartTypeName === 'pie-donut') {
            $rowsToFields = (array)$rowsToFields->first();
            $series = array_values(array_intersect_key($rowsToFields, array_flip($dataIndexes)));
            return $series;
        }
        foreach ($dataIndexes as $dataIndex) {
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
