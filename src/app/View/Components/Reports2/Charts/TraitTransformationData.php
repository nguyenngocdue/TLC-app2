<?php

namespace App\View\Components\Reports2\Charts;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;

trait TraitTransformationData
{
    public function makeRowsToFields($queriedData, $tableColumns)
    {
        // transform queriedData to rows to fields format.
        $result = [];
        foreach ($queriedData as $row) {
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

    public function makeSeriesChart($rowsToFields)
    {
        $series = [];
        $chartTypeName = $this->chartTypeName ?? '';

        $dataIndexes = array_keys($rowsToFields);
        if ($chartTypeName === 'pie-donut') {
            $rowsToFields = (array)$rowsToFields->first();
            $series = array_values(array_intersect_key($rowsToFields, array_flip($dataIndexes)));
            return $series;
        }
        foreach ($dataIndexes as $dataIndex) {
            $name = isset($item['title']) ? $item['title'] : $dataIndex;
            $series[] = (object)[
                'name' => $name,
                'data' => $rowsToFields[$dataIndex] ?? [],
            ];
        }
        return $series;
    }

    public function groupNames($collection)
    {
        $grouped = [];
        foreach ($collection as $item) {
            foreach ($item as $key => $value) {
                $grouped[$key][] = $value;
            }
        }

        return $grouped;
    }
}
