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
    protected $headerTop = null;
    protected $mode = 'status_only';
    protected $apiToCallWhenCreateNew = 'storeEmpty';
    protected $attOfCellToRender = "status";
    protected $showLegend = true;
    protected $showPrintButton = false;
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
    protected function getYAxisPrevious()
    {
        return [];
    }

    protected function getXAxis()
    {
        return [];
    }
    protected function getXAxisPrevious()
    {
        return collect();
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

    protected function getValueToRender($status, $document, $forExcel)
    {
        if ($this->attOfCellToRender == 'status') {
            $value = $status['icon'] ? $status['icon'] : $document->status;
            if ($forExcel) $value = $document->status;
            return $value;
        }
        $value = $document->{$this->attOfCellToRender};
        // dump($value);
        return $value ? $value : '';
    }

    protected function makeStatus($document, $forExcel)
    {
        $status = $this->statuses[$document->status] ?? null;
        if (!is_null($status)) {
            $value = $this->getValueToRender($status, $document, $forExcel);
            [$bgColor, $textColor] = $this->getBackgroundColorAndTextColor($document);
            $item = [
                'value' => $value,
                'cell_title' => 'Open this document (' . $status['title'] . ')',
                'cell_class' => "$bgColor $textColor",
                'cell_href' => route($this->type . ".edit", $document->id),
            ];
            return (object) $item;
        } else {
            // dump("Status not found: " . $document->status . " #" . $document->id);
            return (object)['value' => "unknown status [" . $document->status . "] ???",];
        }
    }
    private function getBackgroundColorAndTextColor($document){
        $status = $this->statuses[$document->status] ?? null;
        if (!is_null($status)) {
            $bgColor = "bg-" . $status['color'] . "-" . $status['color_index'];
            $textColor = "text-" . $status['color'] . "-" . (1000 - $status['color_index']);
        }
        $bgColor = $bgColor ?? '';
        $textColor = $textColor ?? '';
        return [$bgColor, $textColor];
    }
    protected function makeCheckbox($document, $forExcel){
       
        $id = $document->id;
        [$bgColor, $textColor] = $this->getBackgroundColorAndTextColor($document);
        $item = [
            'value' => "<div><input type='checkbox' name='$id'/></div>",
            'cell_title' => 'Select check box id:'.$id,
            'cell_class' => "$bgColor $textColor",
        ];
        return (object) $item;
    }

    function cellRenderer($cell, $dataIndex, $forExcel = false)
    {
        $result = [];
        switch ($dataIndex) {
            case 'checkbox':
                foreach ($cell as $document) {
                    $result[] = $this->makeCheckbox($document, $forExcel);
                }
                break;
            case 'status_only':
            case 'status':
            case 'detail':
                foreach ($cell as $document) {
                    $result[] = $this->makeStatus($document, $forExcel);
                }
                break;
            default:
                # code...
                break;
        }
        if (sizeof($result) == 1) return $result[0];
        return $result;
        // dump($result);
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

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel)
    {
        return [];
    }
    function mergeDataSource($xAxis, $yAxis, $yAxisTableName, $dataSource, $forExcel)
    {
        $dataSource = $this->reIndexDataSource($dataSource);
        $result = [];
        $routeCreate = route($this->type . '.' . $this->apiToCallWhenCreateNew);
        $extraColumns = $this->getXAxisExtraColumns();

        foreach ($yAxis as $y) {
            $yId = $y->id;
            $line = [];
            $line['name_for_group_by'] = $y->name;
            $line['name'] = (object)[
                'value' => $y->name,
                'cell_title' => "(#" . $y->id . ")",
                'cell_class' => "text-blue-800 bg-white",
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
                    $api = "callApi" . ucfirst($this->apiToCallWhenCreateNew);
                    $line[$xId] = (object)[
                        'value' => '<i class="fa-duotone fa-circle-plus"></i>',
                        'cell_href' => 'javascript:' . $api . '("' . $routeCreate . '",[' . $paramStr . '], ' . $metaStr . ')',
                        'cell_class' => "text-center text-blue-800 $xClass",
                        'cell_title' => "Create a new document",
                        'cell_onclick' => "$(this).hide()",
                    ];
                }
            }
            foreach ($xAxis as $x) {
                $xId = $x['dataIndex'];
                if (isset($dataSource[$yId][$xId])) {
                    $line[$xId] = $this->cellRenderer($dataSource[$yId][$xId], $this->mode, $forExcel);
                    if ($this->mode == 'detail') {
                        foreach ($extraColumns as $column) {
                            $line[$xId . "_" . $column] = $this->cellRenderer($dataSource[$yId][$xId], $column, $forExcel);
                        }
                    }
                }
            }
            $metaObjects = $this->getMetaObjects($y, $dataSource, $xAxis, $forExcel);
            foreach ($metaObjects as $key => $metaObject) {
                $newObject = $metaObject;
                if (!is_object($metaObject)) {
                    $newObject = (object) [
                        "cell_class" => "bg-white",
                        "value" => $metaObject,
                    ];
                }
                $line[$key] =  $newObject;
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

    protected function getMetaColumns()
    {
        return [];
    }

    protected function getRightMetaColumns()
    {
        return [];
    }

    protected function getColumns($extraColumns)
    {
        return  [
            ['dataIndex' => 'name_for_group_by', 'hidden' => true],
            ['dataIndex' => 'name', 'width' => 250, 'fixed' => 'left',],
            ...$this->getMetaColumns(),
            ...$extraColumns,
            ...$this->getRightMetaColumns(),
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
        $filterName = "";
        switch ($this->type) {
            case "ghg_sheets":
            case "hse_extra_metrics":
                $filterName = "select_year";
                break;
            case "hr_timesheet_workers":
            case "site_daily_assignments":
                $filterName = "select_week_or_month";
                break;
            default:
                $filterName = $this->type;
                break;
        }

        $viewName = 'components.renderer.view-all-matrix-filter.' . $filterName;
        if (view()->exists($viewName)) {
            return Blade::render('<x-renderer.view-all-matrix-filter.' . $filterName . ' :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>', $params);
        } else {
            return "Unknown type $this->type in type matrix filter (ViewAllTypeMatrixParent.getFilter)";
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
        $actionButtons = "<x-form.action-button-group-view-matrix type='$this->type' groupBy='$this->groupBy' groupByLength='$this->groupByLength' printButton='$this->showPrintButton'/>";
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
                'showPrintButton' => $this->showPrintButton,
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
