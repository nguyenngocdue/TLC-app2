<?php

namespace App\View\Components\Renderer\ViewAll;

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
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
    protected $rotate45Width = false;
    protected $groupBy = 'name_for_group_by';
    protected $groupByLength = 2;
    protected $allowCreation = true;
    protected $tableTrueWidth = false;
    protected $headerTop = '';
    protected $mode = 'status_only';
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
    }

    protected function getYAxis()
    {
        return [];
    }

    protected function getXAxis()
    {
        return [];
    }

    protected function getXAxisExtraColumns()
    {
        return [];
    }

    protected function getXAxis2ndHeader($xAxis)
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

    function cellRenderer($cell, $dataIndex, $forExcel = false)
    {
        $result = [];
        foreach ($cell as $document) {
            $status = $this->statuses[$document->status] ?? null;
            if (!is_null($status)) {
                $bgColor = "bg-" . $status['color'] . "-" . $status['color_index'];
                $textColor = "text-" . $status['color'] . "-" . (1000 - $status['color_index']);
                $value = $status['icon'] ? $status['icon'] : $document->status;
                if ($forExcel) $value = $document->status;
                $result[] = (object)[
                    'value' => $value,
                    'cell_title' => 'Open this document',
                    'cell_class' => "$bgColor $textColor",
                    'cell_href' => route($this->type . ".edit", $document->id),
                ];
            } else {
                // dump("Status not found: " . $document->status . " #" . $document->id);
                $result[] = (object)[
                    'value' => $document->status . " ???",
                ];
            }
        }
        // dump($result);
        if (sizeof($result) == 1) return $result[0];
        return $result;
        // return [1, 2];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = [
            $this->dataIndexX => $x['dataIndex'],
            $this->dataIndexY => $y->id,
        ];
        // dump($params);
        return $params;
    }

    function getMetaObjects($y, $dataSource, $xAxis)
    {
        return [];
    }

    function mergeDataSource($xAxis, $yAxis, $yAxisTableName, $dataSource, $forExcel)
    {
        $dataSource = $this->reIndexDataSource($dataSource);
        $result = [];
        $routeCreate = route($this->type . '.storeEmpty');
        $extraColumns = $this->getXAxisExtraColumns();

        foreach ($yAxis as $y) {
            $yId = $y->id;
            $line = [];
            $line['name_for_group_by'] = $y->name;

            $line['name'] = (object)[
                'value' => $y->name,
                'cell_title' => "(#" . $y->id . ")",
                'cell_class' => "text-blue-800",
                'cell_href' => route($yAxisTableName . ".edit", $y->id),
            ];
            if ($this->allowCreation) {
                $meta['caller'] = 'view-all-matrix';
                $metaStr = json_encode($meta);
                foreach ($xAxis as $x) {
                    if (isset($x['isExtra']) && $x['isExtra']) continue;
                    $xId = $x['dataIndex'];
                    $xClass = $x['column_class'] ?? "";
                    $paramStr = $this->getCreateNewParams($x, $y);
                    // dump($paramStr);
                    $paramStr = (json_encode($paramStr));
                    // [{team_id:' . $yId . ', ts_date:"' . $xId . '", assignee_1:' . $y->def_assignee . '}]
                    $line[$xId] = (object)[
                        'value' => '<i class="fa-duotone fa-circle-plus"></i>',
                        'cell_href' => 'javascript:callApiStoreEmpty("' . $routeCreate . '",[' . $paramStr . '], ' . $metaStr . ')',
                        'cell_class' => "text-center text-blue-800 $xClass",
                        'cell_title' => "Create a new document",
                        'cell_onclick' => "$(this).hide()",
                    ];
                }
            }
            foreach ($xAxis as $x) {
                $xId = $x['dataIndex'];
                if (isset($dataSource[$yId][$xId])) {
                    $line[$xId] = $this->cellRenderer($dataSource[$yId][$xId], 'status', $forExcel);
                    if ($this->mode == 'detail') {
                        foreach ($extraColumns as $column) {
                            $line[$xId . "_" . $column] = $this->cellRenderer($dataSource[$yId][$xId], $column, $forExcel);
                        }
                    }
                }
            }
            $metaObjects = $this->getMetaObjects($y, $dataSource, $xAxis);
            foreach ($metaObjects as $key => $metaObject)  $line[$key] = $metaObject;
            $result[] = $line;
        }
        // dump($result);
        return $result;
    }

    protected function getFilterDataSource()
    {
        return [];
    }

    protected function getMetaColumns()
    {
        return [];
    }

    protected function getColumns($extraColumns)
    {
        return  [
            ['dataIndex' => 'name_for_group_by', 'hidden' => true],
            ['dataIndex' => 'name', 'width' => 250,],
            ...$this->getMetaColumns(),
            ...$extraColumns,
        ];;
    }

    protected function getViewportParams()
    {
        return [];
    }

    private function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    protected function getFooter($yAxisTableName)
    {
        $yAxisRoute = route($yAxisTableName . ".index");
        $app = LibApps::getFor($yAxisTableName);
        return "<div class='flex items-center justify-start'>
                <span class='mr-1'>View all </span>
                <a target='_blank' class='text-blue-400 cursor-pointer font-semibold' href='$yAxisRoute'> " . $app['title'] . "</a>
            </div>";
    }

    private function getFilter()
    {
        $filterDataSource  = $this->getFilterDataSource();
        $viewportParams = $this->getViewportParams();
        //qaqc_wirs.blade.php //FULL TEXT SEARCH HERE
        $params = ["type" => $this->type, "dataSource" => $filterDataSource, "viewportParams" => $viewportParams,];
        $viewName = 'components.renderer.view-all-matrix-filter.' . $this->type;
        if (view()->exists($viewName)) {
            return Blade::render('<x-renderer.view-all-matrix-filter.' . $this->type . ' :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>', $params);
        } else {
            return "Unknown type $this->type in type matrix filter (ViewAllTypeMatrixFilter)";
        }
    }

    public function render()
    {
        [$yAxisTableName, $columns, $dataSource, $xAxis2ndHeading] = $this->getViewAllMatrixParams();
        $footer = $this->getFooter($yAxisTableName);
        $settings = CurrentUser::getSettings();
        $per_page = $settings[$this->type]['view_all']['per_page'] ?? 15;
        $page = $settings[$this->type]['view_all']['matrix']['page'] ?? 1;
        $dataSource = $this->paginate($dataSource, $per_page, $page);
        $route = route('updateUserSettings');
        $perPage = "<x-form.per-page type='$this->type' route='$route' perPage='$per_page'/>";
        $filterRenderer = $this->getFilter();
        $actionButtons = "<x-form.action-button-group-view-matrix type='$this->type' groupBy='$this->groupBy' groupByLength='$this->groupByLength'/>";
        return view(
            'components.renderer.view-all.view-all-type-matrix-parent',
            [
                'columns' => $columns,
                'dataSource' => $dataSource,
                'dataHeader' => $xAxis2ndHeading,
                'type' => $this->type,
                'filterRenderer' => $filterRenderer,
                'footer' => $footer,
                'perPage' => $perPage,
                'rotate45Width' => $this->rotate45Width,
                'groupBy' => $this->groupBy,
                'groupByLength' => $this->groupByLength,
                'tableTrueWidth' => $this->tableTrueWidth,
                'actionButtons' => $actionButtons,
                'headerTop' => $this->headerTop,
            ],
        );
    }
    public function getViewAllMatrixParams($forExcel = false)
    {
        $xAxis = $this->getXAxis();
        $xAxis2ndHeading = $this->getXAxis2ndHeader($xAxis);
        $yAxis = $this->getYAxis();
        $yAxisTableName = (new $this->yAxis)->getTableName();
        $dataSource = $this->getMatrixDataSource($xAxis);
        $dataSource = $this->mergeDataSource($xAxis, $yAxis, $yAxisTableName, $dataSource, $forExcel);
        $columns = $this->getColumns($xAxis);
        return [$yAxisTableName, $columns, $dataSource, $xAxis2ndHeading];
    }
}
