<?php

namespace App\View\Components\Renderer\ViewAll;

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Prod_order;
use App\Models\User;
use App\Models\User_team_tsht;
use App\Models\Wir_description;
use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;
use Illuminate\Support\Str;

abstract class ViewAllTypeMatrixParent extends Component
{
    protected $type = null;
    protected $typeModel = null;
    protected $yAxis = null;
    protected $xAxis = null;
    protected $statuses = null;

    protected $dataIndexX = null;
    protected $dataIndexY = null;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->type = CurrentRoute::getTypePlural();
        $this->typeModel = Str::modelPathFrom($this->type);
        $this->statuses = LibStatuses::getFor($this->type);
        $this->setAxisClass();
    }

    function setAxisClass()
    {
        switch ($this->type) {
            case 'hr_timesheet_workers':
                $this->yAxis =  User_team_tsht::class;
                break;
            case 'qaqc_wirs':
                $this->yAxis =  Prod_order::class;
                $this->xAxis = Wir_description::class;
                break;
            default:
                dump("Unknown how to render matrix of view all type for " . $this->type);
                break;
        }
    }

    function getMeta01Object($y)
    {
        switch ($this->type) {
            case 'hr_timesheet_workers':
                return (object) [
                    'value' => User::findFromCache($y->def_assignee)->name,
                    'cell_title' => $y->def_assignee,
                ];
            case 'qaqc_wirs':
                return $y->production_name;
            default:
                return (object) [
                    'value' => "Unknown meta01 getter.",
                ];
                break;
        }
    }

    function getMeta02Object($y)
    {
        switch ($this->type) {
            case 'hr_timesheet_workers':
                return count($y->getTshtMembers());
            default:
                return "Unknown meta02 getter.";
                break;
        }
    }

    protected function getYAxis()
    {
        return [];
    }

    protected function getXAxis()
    {
        return [];
    }

    protected function getMatrixDataSource($xAxis)
    {
        return [];
    }

    function reIndexDataSource($dataSource)
    {
        $result = [];

        foreach ($dataSource as $line) {
            $result[$line->{$this->dataIndexY}][$line->{$this->dataIndexX}][] = $line;
        }
        return $result;
    }

    function cellRenderer($cell)
    {
        // return ($cell);
        $result = [];
        // dump($statuses);
        foreach ($cell as $document) {
            $status = $this->statuses[$document->status];
            $result[] = (object)[
                'value' => $status['icon'],
                'cell_title' => 'Open this document',
                $bgColor = "bg-" . $status['color'] . "-" . $status['color_index'],
                $textColor = "text-" . $status['color'] . "-" . (1000 - $status['color_index']),
                'cell_class' => "$bgColor $textColor",
                'cell_href' => route($this->type . ".edit", $document->id),
            ];
        }
        // dump($result);
        if (sizeof($result) == 1) return $result[0];
        return $result;
        // return [1, 2];
    }

    protected function getCreateNewParams($x, $y)
    {
        return [
            $this->dataIndexX => $x['dataIndex'],
            $this->dataIndexY => $y->id,
        ];
    }

    function mergeDataSource($xAxis, $yAxis, $yAxisTableName, $dataSource)
    {
        $dataSource = $this->reIndexDataSource($dataSource);
        $result = [];
        $routeCreate = route($this->type . '.storeEmpty');

        foreach ($yAxis as $y) {
            $yId = $y->id;
            $line['name_for_group_by'] = $y->name;

            $line['name'] = (object)[
                'value' => $y->name,
                'cell_title' => "(#" . $y->id . ")",
                'cell_class' => "text-blue-800",
                'cell_href' => route($yAxisTableName . ".edit", $y->id),
            ];
            $line['meta01'] = $this->getMeta01Object($y);
            $line['count'] = $this->getMeta02Object($y);
            foreach ($xAxis as $x) {
                $xId = $x['dataIndex'];
                $xClass = $x['column_class'] ?? "";
                $paramStr = $this->getCreateNewParams($x, $y);
                $paramStr = (json_encode($paramStr));
                // [{team_id:' . $yId . ', ts_date:"' . $xId . '", assignee_1:' . $y->def_assignee . '}]
                $line[$xId] = (object)[
                    'value' => '<i class="fa-duotone fa-circle-plus"></i>',
                    'cell_href' => 'javascript:callApiStoreEmpty("' . $routeCreate . '",[' . $paramStr . '])',
                    'cell_class' => "text-center text-blue-800 $xClass",
                    'cell_title' => "Create a new document",
                ];
            }
            foreach ($xAxis as $x) {
                $xId = $x['dataIndex'];
                if (isset($dataSource[$yId][$xId])) {
                    $line[$xId] = $this->cellRenderer($dataSource[$yId][$xId]);
                }
            }
            $result[] = $line;
        }
        // dump($result);
        return $result;
    }

    protected function getFilterDataSource()
    {
        return [];
    }

    protected function getColumns($extraColumns)
    {
        return  [
            ['dataIndex' => 'name_for_group_by', 'hidden' => true],
            ['dataIndex' => 'name', 'width' => 250,],
            ['dataIndex' => 'meta01', 'title' => 'Name', 'width' => 150,],
            ['dataIndex' => 'count', 'align' => 'center', 'width' => 50],
            ...$extraColumns,
        ];;
    }

    protected function getViewportParams()
    {
        return [];
    }

    public function render()
    {
        $xAxis = $this->getXAxis();
        // dump($xAxis);
        $yAxis = $this->getYAxis();
        $yAxisTableName = (new $this->yAxis)->getTableName();
        $dataSource = $this->getMatrixDataSource($xAxis);
        $dataSource = $this->mergeDataSource($xAxis, $yAxis, $yAxisTableName, $dataSource);
        $columns = $this->getColumns($xAxis);

        $yAxisRoute = route($yAxisTableName . ".index");
        $app = LibApps::getFor($yAxisTableName);
        $footer = "<a target='_blank' href='$yAxisRoute'>" . $app['title'] . "</a>";

        return view(
            'components.renderer.view-all.view-all-type-matrix-parent',
            [
                'columns' => $columns,
                'dataSource' => $dataSource,
                'type' => $this->type,
                'filterDataSource' => $this->getFilterDataSource(),
                'viewportParams' => $this->getViewportParams(),
                'footer' => $footer,
            ],
        );
    }
}
