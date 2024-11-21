<?php

namespace App\View\Components\Renderer\MatrixForReport;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Log;

abstract class MatrixForReportParent extends Component
{
    protected $dataIndexX = "wir_description_id";
    protected $dataIndexY = "prod_order_id";
    protected $rotate45Width = 400;
    protected $rotate45Height = null;

    protected $headerTop = 40 * 16;
    protected $maxH = 40 * 16;

    protected $closedDateColumn = 'closed_at';

    protected $statuses;

    protected $finishedArray = null;
    protected $naArray = null;

    protected $groupBy = null;
    protected $groupByLength = null;

    function __construct(
        private $type,
        private $dateToCompare = null,
        // private $dateToCompare = '2023-10-01',
    ) {
        $this->statuses = LibStatuses::getFor($type);
        $this->finishedArray = LibStatuses::$finishedArray;
        $this->naArray = LibStatuses::$naArray;
    }

    abstract function getXAxis();
    abstract function getYAxis();
    abstract function getDataSource($xAxis, $yAxis);

    function cellRenderer($cell, $xAxis, $yAxis, $dataSource, $forExcel = false, $matrixKey = null)
    {
        if (isset($cell->status)) {
            $id = $cell->id;
            $name = $cell->name;
            $href = route($this->type . ".edit", $id);

            $status = $cell->status;
            $statusObj = $this->statuses[$status];
            $value = $statusObj['icon'];
            $cellClass = 'bg-' . $statusObj['bg_color'] . " text-" . $statusObj['text_color'];

            if ($this->dateToCompare) {
                if (($endDate = $cell->{$this->closedDateColumn})) {
                    if (in_array($cell->status, $this->finishedArray)) {
                        if ($endDate < $this->dateToCompare) {
                            $cellClass .= ' bg-opacity-10 ';
                        }
                    } else {
                        return "";
                    }
                } else {
                    if (in_array($cell->status, $this->naArray)) {
                        //Keep N/A and canceled
                        $cellClass .= ' bg-opacity-10 ';
                    } else {
                        return "";
                    }
                }
            }

            if ($forExcel) return $cell->status;

            return (object)[
                "value" => $value, //. " " . $cell->{$this->dataIndexX},
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

    function renderCell($xAxis, $yAxis, $dataSource, $forExcel = false)
    {
        $result = [];
        foreach ($dataSource as $yId => $columns) {
            foreach ($xAxis as $x) {
                $result[$yId][$x->id] = "<div class='text-center'>-</div>"; //Make placeholder, incase if null, export to excel will have cell with -
            }
        }
        foreach ($dataSource as $yId => $columns) {
            foreach ($columns as $xId => $item) {
                $result[$yId][$xId] = $this->cellRenderer($item, $xAxis, $yAxis, $dataSource, $forExcel);
            }
        }
        return $result;
    }

    function getLeftColumns($xAxis, $yAxis, $dataSource)
    {
        return [
            ['dataIndex' => 'name', 'fixed' => 'left',],
        ];
    }

    function getColumns($xAxis)
    {
        $result = [];
        foreach ($xAxis as $x) {
            $column = [
                'dataIndex' => $x->id,
                'title' => $x->name,
                // 'columnDivStyle' => ['z-index' => 2],
                // 'width' => 40,
            ];
            if ($x->width) $column['width'] = $x->width;
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
                'cell_title' => $y->id,
            ];
        }
        return $dataSource;
    }

    function sortBy($column, $dataSource)
    {
        // try {
        usort($dataSource, function ($a, $b) use ($column) {
            $x = $a[$column] ?? 0;
            $y = $b[$column] ?? 0;
            if (is_object($x)) $x = $x->value;
            if (is_object($y)) $y = $y->value;
            return $x <=> $y;
        });
        // } catch (\Exception $e) {
        //     dd($dataSource);
        // }
        return $dataSource;
    }

    function getWeightArray($xAxis, $yAxis, $dataSource)
    {
        $result = $xAxis->pluck('wir_weight', 'id')->toArray();
        $allNull = Arr::allElementsAre($result, null);
        if ($allNull) {
            foreach (array_keys($result) as $key) $result[$key] = 1;
        }

        // dump($result);
        return $result;
    }

    function removeWeightOfNA($wa, $line)
    {
        foreach ($line as $cell) if (in_array($cell->status, $this->naArray)) unset($wa[$cell->{$this->dataIndexX}]);
        return $wa;
    }

    function calculateProgressForRows($xAxis, $yAxis, $dataSource)
    {
        $weightArray = $this->getWeightArray($xAxis, $yAxis, $dataSource);

        $result = [];
        // dump($dataSource);
        // dump(array_pop($dataSource));
        foreach ($dataSource as $id => $line) {
            $wa = $this->removeWeightOfNA($weightArray, $line);
            $totalWa = array_sum($wa);
            // dump($wa);
            $result[$id]['progress'] = 0;
            // dump($line);
            foreach ($line as $cell) {
                if (in_array($cell->status, $this->finishedArray)) {
                    $value = $wa[$cell->{$this->dataIndexX}] ?? 0;
                    $result[$id]['progress'] += 100 * $value / $totalWa;
                }
            }
        }

        // dump($dataSource);
        foreach ($dataSource as $id => &$line) {
            $line['progress'] = (object)[
                'value' => number_format($result[$id]['progress'], 2) . '%',
                'cell_class' => 'text-right',
            ];
        }
        return $dataSource;
    }

    function calculateProgressForColumns($xAxis, $yAxis, $dataSource, $leftColumns)
    {
        $result = [];
        $count = [];
        foreach ($xAxis as $x) {
            $result[$x->id] = 0;
            $count[$x->id] = 0;
        }
        $totalProgress = 0;
        foreach ($dataSource as $line) {
            foreach ($line as $cell) {
                if (isset($cell->status)) { // row progress is a number
                    if (in_array($cell->status, $this->finishedArray)) {
                        $result[$cell->{$this->dataIndexX}]++;
                    }
                    if (in_array($cell->status, $this->naArray)) {
                        $count[$cell->{$this->dataIndexX}]++;
                    }
                }
            }
            $v = $line['progress']->value ?? '0%';
            $totalProgress += substr($v, 0, strlen($v) - 1);
        }

        $totalRows = count($yAxis);

        foreach ($result as $xId => &$line) {
            $tu = $line;
            $mau = $totalRows - $count[$xId];
            if ($mau > 0) {
                $percent = number_format(100 * $line / $mau) . '%';
                $line = (object)[
                    'value' =>  $tu . '/' . $mau . ' <br/>' . $percent . "",
                    'cell_class' => "text-center text-xs",
                ];
            } else {
                $line = "<div class='w-10 text-center text-xs'>NA</div>";
            }
        }

        $result['name'] = (object)[
            'value' => "",
            'cell_class' => 'text-center font-bold',
        ];

        $dataIndex = end($leftColumns)['dataIndex'];
        $result[$dataIndex] = (object)[
            'value' => "Total",
            'cell_class' => 'text-center font-bold bg-gray-100',
        ];

        $size = sizeof($yAxis);
        $result['name'] = ''; // For excel column span
        $result['production_name'] = ''; // For excel column span
        $result['quantity'] = ''; // For excel column span
        if ($size) {
            $totalProgress /= $size;
            $result['progress'] = (object)[
                'value' => number_format($totalProgress, 2) . '%',
                'cell_class' => 'text-right font-bold bg-gray-100',
            ];
            $dataSource[] = $result;
        }
        // dump($dataSource);
        return $dataSource;
    }

    function getXAxis2ndHeader($xAxis)
    {
        return [];
    }

    function getMatrixForReportParams($forExcel = false)
    {
        $xAxis = $this->getXAxis();
        $yAxis = $this->getYAxis();
        $dataSource = $this->getDataSource($xAxis, $yAxis);

        $leftColumns = $this->getLeftColumns($xAxis, $yAxis, $dataSource);
        $columns = [
            ...$leftColumns,
            ...$this->getColumns($xAxis),
        ];
        $dataSource = $this->mergeDataSource($xAxis, $yAxis, $dataSource);

        $dataSource = $this->calculateProgressForRows($xAxis, $yAxis, $dataSource);
        $dataSource = $this->attachMeta($xAxis, $yAxis, $dataSource);
        $dataSource = $this->sortBy('name', $dataSource);
        $dataSource = $this->calculateProgressForColumns($xAxis, $yAxis, $dataSource, $leftColumns);
        $dataSource = $this->renderCell($xAxis, $yAxis, $dataSource, $forExcel);
        // dump($dataSource);

        $xAxis2ndHeading = $this->getXAxis2ndHeader($xAxis);
        return [$columns, $dataSource, $xAxis2ndHeading];
    }

    function getCsvExportParams()
    {
        return [];
    }

    function getActionButtons()
    {
        $actionBtnList = [
            'exportSCV' => true,
            'printTemplate' => false,
            'approveMulti' => false,
        ];
        $params = [
            'groupBy' => $this->groupBy,
            'groupByLength' => $this->groupByLength,
            ...$this->getCsvExportParams(),
        ];

        $actionButtons = Blade::render("<x-form.action-button-group-view-matrix
            routePrefix='_mep2.exportCsvMatrix2'
            type='$this->type'
            :actionBtnList='\$actionBtnList'
            :params='\$params'
            />", [
            'actionBtnList' => $actionBtnList,
            'params' => $params,
        ]);

        return $actionButtons;
    }

    function render()
    {
        [$columns, $dataSource, $xAxis2ndHeading] = $this->getMatrixForReportParams();

        return view('components.renderer.matrix-for-report.matrix-for-report-parent', [
            'columns' => $columns,
            'dataSource' => $dataSource,
            'dataHeader' => $xAxis2ndHeading,
            'rotate45Width' => $this->rotate45Width,
            'rotate45Height' => $this->rotate45Height,
            'type' => $this->type,
            'actionButtons' => $this->getActionButtons(),

            'headerTop' => $this->headerTop,
            'maxH' => $this->maxH,
        ]);
    }
}
