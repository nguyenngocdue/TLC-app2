<?php

namespace App\View\Components\Renderer\MatrixForReport;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Log;

abstract class MatrixForReportParent extends Component
{
    protected $dataIndexX = "wir_description_id";
    protected $dataIndexY = "prod_order_id";
    protected $rotate45Width = 400;
    protected $rotate45Height = null;

    protected $statuses;

    function __construct(
        private $type,
    ) {
        $this->statuses = LibStatuses::getFor($type);
    }

    abstract function getXAxis();
    abstract function getYAxis();
    abstract function getDataSource();

    function cellRenderer($cell, $xAxis, $yAxis, $dataSource)
    {
        if (isset($cell->status)) {

            $id = $cell->id;
            $name = $cell->name;
            $href = route($this->type . ".edit", $id);

            $status = $cell->status;
            $statusObj = $this->statuses[$status];
            $value = $statusObj['icon'];
            $cellClass = 'bg-' . $statusObj['bg_color'] . " text-" . $statusObj['text_color'];
            return (object)[
                "value" => $value,
                'cell_class' => "$cellClass text-center cursor-pointer",
                'cell_title' => $name . " (" . $statusObj['title'] . ")",
                'cell_href' => $href,
            ];
        }
        return $cell;
    }

    function mergeDataSource($xAxis, $yAxis, $dataSource)
    {
        $items = [];
        foreach ($dataSource as $line) {
            $xId = $line->{$this->dataIndexX};
            $yId = $line->{$this->dataIndexY};

            $items[$yId][$xId] = $line;
        }
        return $items;
    }

    function renderCell($xAxis, $yAxis, $dataSource)
    {
        $result = [];
        foreach ($dataSource as $yId => $columns) {
            foreach ($columns as $xId => $item) {
                $result[$yId][$xId] = $this->cellRenderer($item, $xAxis, $yAxis, $dataSource);
            }
        }
        return $result;
    }

    function getLeftColumns($xAxis, $yAxis, $dataSource)
    {
        return [
            ['dataIndex' => 'name',],
            ['dataIndex' => 'progress',],
        ];
    }

    function getColumns($xAxis)
    {
        $result = [];
        foreach ($xAxis as $x) {
            $column = [
                'dataIndex' => $x->id,
                'title' => $x->name,
            ];
            $result[] = $column;
        }
        return $result;
    }

    function attachMeta($xAxis, $yAxis, $dataSource)
    {
        foreach ($yAxis as $y) {
            $dataSource[$y->id]['name'] = (object)[
                'value' => $y->name,
                'cell_class' => "whitespace-nowrap",
            ];
        }
        return $dataSource;
    }

    function sortBy($column, $dataSource)
    {
        usort($dataSource, fn ($a, $b) => $a[$column] <=> $b[$column]);
        return $dataSource;
    }

    function calculateProgress($xAxis, $yAxis, $dataSource)
    {
        $finished = ['closed', 'not_applicable', 'cancelled'];
        $result = [];
        foreach ($dataSource as $id => $line) {
            $result[$id]['progress'] = 0;
            foreach ($line as $cell) {
                if (in_array($cell->status, $finished)) {
                    $result[$id]['progress'] += 1;
                }
            }
        }
        // dump($dataSource);
        foreach ($dataSource as $id => &$line) {
            $line['progress'] = (object)[
                'value' => $result[$id]['progress'],
                'cell_class' => 'text-right',
            ];
        }
        return $dataSource;
    }

    function render()
    {
        $xAxis = $this->getXAxis();
        $yAxis = $this->getYAxis();
        $dataSource = $this->getDataSource();

        $columns = [
            ...$this->getLeftColumns($xAxis, $yAxis, $dataSource),
            ...$this->getColumns($xAxis),
        ];
        $dataSource = $this->mergeDataSource($xAxis, $yAxis, $dataSource);
        $dataSource = $this->calculateProgress($xAxis, $yAxis, $dataSource);
        $dataSource = $this->attachMeta($xAxis, $yAxis, $dataSource);
        $dataSource = $this->sortBy('name', $dataSource);
        $dataSource = $this->renderCell($xAxis, $yAxis, $dataSource);
        // dump($dataSource);

        return view('components.renderer.matrix-for-report.matrix-for-report-parent', [
            'columns' => $columns,
            'dataSource' => $dataSource,
            'rotate45Width' => $this->rotate45Width,
            'rotate45Height' => $this->rotate45Height,
        ]);
    }
}
