<?php

namespace App\View\Components\Reports2\Charts\Types;

use App\Http\Controllers\Reports\TraitCreateSQL;
use App\View\Components\Reports2\TraitDataColumnReport;
use Illuminate\View\Component;

class ChartColumn extends Component
{
    use TraitDataColumnReport;
    public function __construct(
        private $chartType,
        private $dataQuery,
        private $chartJson,
        private $tableColumns,
    ) {
    }

    private function makeRowsToFields($dataQuery, $tableColumns)
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

    private function makeSeriesChart($rowsToFields, $tableColumns)
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

    private function mapDataQueryToColumns($dataQuery, $tableColumns)
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

    public function render()
    {
        $dataQuery = $this->dataQuery;
        $tableColumns = $this->tableColumns;
        $typeOfTrans = "rows_to_fields";

        switch ($typeOfTrans) {
            case 'rows__to_fields':
                $transformation = $this->makeRowsToFields($dataQuery, $tableColumns);
                break;
            default:
                $transformation = $this->mapDataQueryToColumns($dataQuery, $tableColumns);
                break;
        }
        $chartType = $this->chartType;
        $series = $this->makeSeriesChart($transformation, $tableColumns);

        $chartJson = json_decode($this->chartJson);
        $chartJson = array_pop($chartJson);
        return view('components.reports2.charts.types.chart-column', [
            'series' => $series,
            'chartType' => $chartType,
            'chartJson' => $chartJson,
        ]);
    }
}
