<?php

namespace App\View\Components\Renderer\ViewAll;

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

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
    protected $tableTopCenterControl = "";
    protected $checkboxCaptionColumn = null;
    protected $apiCallback = 'null'; //<<JS String
    protected $nameColumnFixed = 'left';
    protected $cellAgg = null;

    protected $actionBtnList = [
        'exportSCV' => true,
        'printTemplate' => false,
        'approveMulti' => false,
    ];

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

    protected function getRouteAfterSubmit()
    {
        if ($this->actionBtnList['printTemplate']) return route($this->type . '_prt.print');
        // if ($this->actionBtnList['approveMulti']) return route($this->type . '_prt.print');
    }

    protected function getYAxis()
    {
        return [];
    }

    protected function getXAxis()
    {
        return [];
    }
    protected function getXAxisPrimaryColumns()
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
            // dump($this->dataIndexY . " - " . $this->dataIndexX);
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
        if (is_numeric($value)) $value = number_format($value, 2);
        // dump($value);
        return $value ? $value : '';
    }

    protected function makeStatus($document, $forExcel, $route = null)
    {
        $status = $this->statuses[$document->status] ?? null;
        if (is_null($route)) {
            $route = route($this->type . ".edit", $document->id);
        }
        if (!is_null($status)) {
            $value = $this->getValueToRender($status, $document, $forExcel);
            [$bgColor, $textColor] = $this->getBackgroundColorAndTextColor($document);
            $item = [
                'value' => $value,
                'cell_title' => 'Open this document (' . $status['title'] . ')',
                'cell_class' => "$bgColor $textColor",
                'cell_href' => $route,
            ];
            return (object) $item;
        } else {
            // dump("Status not found: " . $document->status . " #" . $document->id);
            return (object)[
                'value' => "unknown status [" . $document->status . "] ???",
                'cell_href' => $route,
            ];
        }
    }
    private function getBackgroundColorAndTextColor($document)
    {
        $status = $this->statuses[$document->status] ?? null;
        return ['bg-' . $status['bg_color'], 'text-' . $status['text_color']];
    }

    protected function makeCaptionForCheckbox($document)
    {
        if (is_null($this->checkboxCaptionColumn)) return "";

        $caption = ($th = $document->total_hours) ?  "$th hours" : "...";
        $href = route($this->type . ".edit", $document->id);
        $a = "<a href='$href'>$caption</a>";

        return $a;
    }
    protected function getCheckboxVisible($document, $y)
    {
        return true;
    }

    protected function makeCheckbox($document, $y, $forExcel)
    {
        $isCheckboxVisible = $this->getCheckboxVisible($document, $y) ? 1 : 0;
        $id = $document->id;
        $status = $document->status;
        $yId = $y->id;

        [$bgColor, $textColor] = $this->getBackgroundColorAndTextColor($document);
        $className = $isCheckboxVisible ? "cursor-pointer view-all-matrix-checkbox-$yId" : "cursor-not-allowed disabled:opacity-50";
        $disabledStr = $isCheckboxVisible ? "" : "disabled";
        $route = Route::has($rStr = ($this->type . ".changeStatusMultiple")) ? route($rStr) : "null";
        $checkbox = "<input $disabledStr onclick='determineNextStatuses(\"$id\", \"$status\", this.checked, \"$route\")' status='$status' class='$className' title='" . Str::makeId($id) . "' type='checkbox' id='checkbox_{$yId}_$id' name='$id'/>";
        $item = [
            'value' => $checkbox . "<br/>" . $this->makeCaptionForCheckbox($document),
            // 'cell_title' => 'Select check box id:' . $id,
            'cell_class' => "$bgColor $textColor",
        ];
        return (object) $item;
    }

    function cellRenderer($cell, $dataIndex, $x, $y, $forExcel = false)
    {
        $result = [];
        switch ($dataIndex) {
            case 'checkbox_change_status':
            case 'checkbox_print':
                foreach ($cell as $document) $result[] = $this->makeCheckbox($document, $y, $forExcel);
                break;
            case 'status_only':
                // case 'status':
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
        // dump($xAxis);
        // dd($yAxis);
        $dataSource = $this->reIndexDataSource($dataSource);
        $result = [];
        $api_url = route($this->type . '.' . $this->apiToCallWhenCreateNew);
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
                'cell_div_class' => 'whitespace-nowrap',
            ];
            if ($this->allowCreation) {
                $meta['caller'] = 'view-all-matrix';
                $api_meta = json_encode($meta);
                foreach ($xAxis as $x) {
                    if (isset($x['isExtra']) && $x['isExtra']) continue;
                    $xId = $x['dataIndex'];
                    $xClass = $x['column_class'] ?? "";
                    $api_params = $this->getCreateNewParams($x, $y);
                    // dump($api_params);
                    $api_params = (json_encode($api_params));
                    $api_callback = $this->apiCallback;
                    // [{team_id:' . $yId . ', ts_date:"' . $xId . '", assignee_1:' . $y->def_assignee . '}]
                    $api = "callApi" . ucfirst($this->apiToCallWhenCreateNew);
                    $line[$xId] = (object)[
                        'value' => '<i class="fa-duotone fa-circle-plus"></i>',
                        'cell_href' => 'javascript:' . $api . '("' . $api_url . '",[' . $api_params . '], ' . $api_meta . ', ' . $api_callback . ')',
                        'cell_class' => "text-center text-blue-800 $xClass",
                        'cell_title' => "Create a new document",
                        'cell_onclick' => "$(this).hide()",
                    ];
                    if ($this->mode == 'detail') {
                        foreach ($extraColumns as $column) {
                            //<<create empty cell in excel file
                            $line[$xId . "_" . $column] = "";
                        }
                    }
                }
            }
            foreach ($xAxis as $x) {
                $xId = $x['dataIndex'];
                $hasFile = isset($dataSource[$yId][$xId]);
                // dump("yID $yId xId $xId $hasFile");
                if ($hasFile) {
                    $value = $this->cellRenderer($dataSource[$yId][$xId], $this->mode, $x, $y, $forExcel);
                    $line[$xId] = $value;
                    if ($this->mode == 'detail') {
                        foreach ($extraColumns as $column) {
                            $key = $xId . "_" . $column;
                            $value = $this->cellRenderer($dataSource[$yId][$xId], $column, $x, $y, $forExcel);
                            //<< convert to empty string for excel
                            $line[$key] = is_null($value) ? "" : $value;
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
            ['dataIndex' => 'name', 'width' => 300, 'fixed' => $this->nameColumnFixed,],
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
        if (is_array($items)) $items = collect($items);
        $count = $items->count();

        //<< If current page of HLC is 12, but STW only have 1 page
        //Force the program to select the smallest page
        $page = ($count) ? min(ceil($count / $perPage), $page) : 1; //<< This line has bug
        // Log::info("Count $count, perPage: $perPage, Page: $page");

        return new LengthAwarePaginator($items->forPage($page, $perPage), $count, $perPage, $page, $options);
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

        $params = ["type" => $this->type, "dataSource" => $filterDataSource, "viewportParams" => $viewportParams,];
        $filterName = "";
        switch ($this->type) {
            case "esg_master_sheets":
                $filterName = "select_year_and_workplace";
                break;
            case "ghg_sheets":
            case "hse_extra_metrics":
            case "hse_insp_chklst_shts":
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
            //qaqc_wirs.blade.php //FULL TEXT SEARCH HERE
            return "Filter $this->type.blade.php file not found (ViewAllTypeMatrixParent.getFilter, search qaqc_wirs.blade.php)";
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

        $actionButtons = Blade::render("<x-form.action-button-group-view-matrix
            type='$this->type'
            groupBy='$this->groupBy'
            groupByLength='$this->groupByLength'
            :actionBtnList='\$actionBtnList'
            />", [
            'actionBtnList' => $this->actionBtnList,
        ]);
        // dd($columns);
        // dump($dataSource[0]);

        // dump($perPage);
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
                'tableTopCenterControl' => $this->tableTopCenterControl,
                'route' => $this->getRouteAfterSubmit(),
            ],
        );
    }
    private function aggArrayOfCells($dataSource)
    {
        if ($this->cellAgg) {
            if (!$this->allowCreation) {
                foreach ($dataSource as &$row) {
                    foreach ($row as &$cells) {
                        if (is_array($cells)) {
                            $agg_value = 0;
                            foreach ($cells as $cell) {
                                $agg_value += $cell->value;
                            }
                            $cells = $cells[0];
                            $cells->value = number_format($agg_value, 2);
                            $cells->cell_href = "";
                            $cells->cell_title = "";
                        }
                        if (is_object($cells)) {
                            $cells->cell_href = "";
                            $cells->cell_title = "";
                        }
                    }
                }
            }
        }
        return $dataSource;
    }
    public function getViewAllMatrixParams($forExcel = false)
    {
        $xAxis = $this->getXAxis();
        $xAxis2ndHeading = $this->getXAxis2ndHeader($xAxis);
        $yAxis = $this->getYAxis();
        $yAxisTableName = (new $this->yAxis)->getTableName();
        $dataSource = $this->getMatrixDataSource($xAxis);
        $dataSource = $this->mergeDataSource($xAxis, $yAxis, $yAxisTableName, $dataSource, $forExcel);
        $dataSource = $this->aggArrayOfCells($dataSource);
        // dd($dataSource[0]);
        $columns = $this->getColumns($xAxis);
        return [$yAxisTableName, $columns, $dataSource, $xAxis2ndHeading];
    }
}
