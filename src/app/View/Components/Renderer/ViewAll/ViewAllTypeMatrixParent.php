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
    protected $rotate45Height = false;
    protected $groupBy = 'name_for_group_by';
    protected $groupByLength = 2;
    protected $allowCreation = true;
    protected $allowCreationPlaceholder = '';
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
    protected $maxH = null;
    protected $multipleMatrix = false;
    protected $matrixes = null;

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
    public function __construct($pluralType = null)
    {
        $this->type = $pluralType ?: CurrentRoute::getTypePlural();
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

    protected function getValueToRender($status, $document, $forExcel, $objectToGet)
    {
        $objectToGet = (is_null($objectToGet) ? $this : $objectToGet);
        if ($objectToGet->attOfCellToRender == 'status') {
            $value = $status['icon'] ? $status['icon'] : $document->status;
            if ($forExcel) $value = $document->status;
            return $value;
        }
        $value = $document->{$objectToGet->attOfCellToRender};
        if (is_numeric($value)) $value = number_format($value, 2);
        // dump($value);
        return $value ? $value : '';
    }

    protected function makeStatus($document, $forExcel, $editRoute = null, $statuses = null, $objectToGet = null, $matrixKey = null)
    {
        if (is_null($statuses)) $statuses = $this->statuses;
        $status = $statuses[$document->status] ?? null;
        // if ($objectToGet) {
        //     dump($status);
        //     dump($statuses);
        // }
        if (is_null($editRoute)) {
            $editRoute = route($this->type . ".edit", $document->id);
        }
        if (!is_null($status)) {
            $value = $this->getValueToRender($status, $document, $forExcel, $objectToGet);
            [$bgColor, $textColor] = $this->getBackgroundColorAndTextColor($document, $statuses);
            $item = [
                'value' => $value,
                'cell_title' => 'Open this document (' . $status['title'] . ')',
                'cell_class' => "$bgColor $textColor text-center",
                'cell_href' => $editRoute,
            ];
            return (object) $item;
        } else {
            // dump("Status not found: " . $document->status . " #" . $document->id);
            return (object)[
                'value' => "unknown status [" . $document->status . "] ???",
                'cell_href' => $editRoute,
            ];
        }
    }
    protected function getBackgroundColorAndTextColor($document, $statuses = null)
    {
        if (is_null($statuses)) $statuses = $this->statuses;
        $status = $statuses[$document->status] ?? null;
        return ['bg-' . $status['bg_color'], 'text-' . $status['text_color']];
    }

    function cellRenderer($cell, $dataIndex, $x, $y, $forExcel = false, $matrixKey = null)
    {
        $result = [];
        switch ($dataIndex) {
            case 'status_only':
            case 'detail':
                foreach ($cell as $document) {
                    $result[] = $this->makeStatus($document, $forExcel, null, null, null, $matrixKey);
                }
                break;
            default:
                # code...
                break;
        }
        if (sizeof($result) == 1) return $result[0];
        return $result;
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

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey)
    {
        return [];
    }
    function makeCreateButton($xAxis, $y, $extraColumns, &$line, $objectToGetCreateNewParams)
    {
        $api_url = route($objectToGetCreateNewParams->type . '.' . $objectToGetCreateNewParams->apiToCallWhenCreateNew);
        $meta['caller'] = 'view-all-matrix';
        $api_meta = json_encode($meta);
        foreach ($xAxis as $x) {
            if (isset($x['isExtra']) && $x['isExtra']) continue;
            $xId = $x['dataIndex'];
            $xClass = $x['column_class'] ?? "";
            $api_params = $objectToGetCreateNewParams->getCreateNewParams($x, $y);

            if (isset($api_params['name'])) {
                //prod_order name CB01' => CB01`
                $api_params['name'] = str_replace("'", "`", $api_params['name']);
            }

            $api_params = (json_encode($api_params));
            // dump($api_params);
            $api_callback = $this->apiCallback;
            // [{team_id:' . $yId . ', ts_date:"' . $xId . '", assignee_1:' . $y->def_assignee . '}]
            $api = "callApi" . ucfirst($objectToGetCreateNewParams->apiToCallWhenCreateNew);
            $cell_href = "javascript:$api(\"$api_url\",[$api_params], $api_meta, $api_callback)";
            // dump($cell_href);
            $line[$xId] = (object)[
                'value' => '<i class="fa-duotone fa-circle-plus"></i>',
                'cell_href' => $cell_href,
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

    function mergeDataSource($xAxis, $yAxis, $yAxisTableName, $dataSource, $forExcel, $matrixKey = null)
    {
        // dump($xAxis);
        // dd($yAxis);
        $dataSource = $this->reIndexDataSource($dataSource);
        $result = [];
        // $api_url = route($this->type . '.' . $this->apiToCallWhenCreateNew);
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
                $this->makeCreateButton($xAxis, $y, $extraColumns, $line, $this);
            } else {
                foreach ($xAxis as $x) {
                    if (isset($x['isExtra']) && $x['isExtra']) continue;
                    $xId = $x['dataIndex'];
                    $line[$xId] = (object)[
                        'value' =>  $this->allowCreationPlaceholder,
                    ];
                }
            }
            foreach ($xAxis as $x) {
                $xId = $x['dataIndex'];
                $hasFile = isset($dataSource[$yId][$xId]);
                // dump("yID $yId xId $xId $hasFile");
                if ($hasFile) {
                    $value = $this->cellRenderer($dataSource[$yId][$xId], $this->mode, $x, $y, $forExcel, $matrixKey);
                    $line[$xId] = $value;
                    if ($this->mode == 'detail') {
                        foreach ($extraColumns as $column) {
                            $key = $xId . "_" . $column;
                            $value = $this->cellRenderer($dataSource[$yId][$xId], $column, $x, $y, $forExcel, $matrixKey);
                            //<< convert to empty string for excel
                            $line[$key] = is_null($value) ? "" : $value;
                        }
                    }
                }
            }
            $metaObjects = $this->getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey);
            foreach ($metaObjects as $key => $metaObject) {
                $newObject = $metaObject;
                if (Str::endsWith($key, "_for_group_by")) {
                    $newObject = $metaObject;
                } else {
                    if (!is_object($metaObject)) {
                        $newObject = (object) [
                            "cell_class" => "bg-white",
                            "value" => $metaObject,
                        ];
                    }
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

    protected function getViewportParams()
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
            ['dataIndex' => 'name', 'fixed' => $this->nameColumnFixed,],
            ...$this->getMetaColumns(),
            ...$extraColumns,
            ...$this->getRightMetaColumns(),
        ];;
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

    protected function getFilter()
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
            case "esg_inductions":
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
            return "Filter $this->type.blade.php file not found (ViewAllTypeMatrixParent.getFilter, Full-text search qaqc_wirs.blade.php)";
        }
    }

    protected function getMultipleMatrixObjects()
    {
        return [];
    }

    private function getPerPage()
    {
        $settings = CurrentUser::getSettings();
        $route = route('updateUserSettings');
        $result = [];

        if ($this->multipleMatrix) {
            foreach (array_keys($this->matrixes) as $key) {
                $per_page = $settings[$this->type]['view_all']['matrix'][$key]['per_page'] ?? 15;
                $page = $settings[$this->type]['view_all']['matrix'][$key]['page'] ?? 1;
                $perPage = "<x-form.per-page type='$this->type' route='$route' perPage='$per_page' key='$key'/>";
                $result[$key] = [$perPage, $per_page, $page];
            }
        } else {
            $per_page = $settings[$this->type]['view_all']/*['matrix']*/['per_page'] ?? 15;
            $page = $settings[$this->type]['view_all']['matrix']['page'] ?? 1;
            $perPage = "<x-form.per-page type='$this->type' route='$route' perPage='$per_page' />";
            $result = [$perPage, $per_page, $page];
        }

        return $result;
    }

    private function getActionButtons(string $key = null)
    {
        $params = [
            'groupBy' => $this->groupBy,
            'groupByLength' => $this->groupByLength,
        ];
        if ($key) {
            $params['key'] = $key;
        }
        $actionButtons = Blade::render("<x-form.action-button-group-view-matrix
            routePrefix='_mep1.exportCsvMatrix1'
            type='$this->type'
            :actionBtnList='\$actionBtnList'
            :params='\$params'
            />", [
            'actionBtnList' => $this->actionBtnList,
            'params' => $params,
        ]);
        return $actionButtons;
    }

    public function render()
    {
        [$yAxisTableName, $columns, $dataSource, $xAxis2ndHeading] = $this->getViewAllMatrixParams();
        $footer = $this->getFooter($yAxisTableName);
        $perPageArray = [];

        if ($this->multipleMatrix) {
            $perPageArray = $this->getPerPage();
            foreach (array_keys($this->matrixes) as $key) {
                [$perPage, $per_page, $page] = $perPageArray[$key];
                $dataSource[$key] = $this->paginate($dataSource[$key], $per_page, $page, ['query' => ['key' => $key]]);
                $perPageArray[$key] = $perPage;
            }
        } else {
            [$perPage, $per_page, $page] = $this->getPerPage();
            $dataSource = $this->paginate($dataSource, $per_page, $page);
            $perPageArray = $perPage;
        }

        $filterRenderer = $this->getFilter();


        // dd($columns);
        // dump($dataSource[0]);
        // dump($xAxis2ndHeading);
        // dump($perPage);

        $matrixes = [];
        if (!$this->multipleMatrix) {
            $matrixes = [
                [
                    'name' => '',
                    'description' => '',
                    'columns' => $columns,
                    'dataSource' => $dataSource,
                    'dataHeader' => $xAxis2ndHeading,
                    'perPage' => $perPage,
                    'actionButtons' => $this->getActionButtons(),
                ],
            ];
        } else {
            foreach ($this->matrixes as $key => $object) {
                $matrixes[] = [
                    'name' => $object['name'],
                    'description' => $object['description'],
                    'columns' => $columns[$key],
                    'dataSource' => $dataSource[$key],
                    'dataHeader' => $xAxis2ndHeading[$key],
                    'perPage' => $perPageArray[$key],
                    'actionButtons' => $this->getActionButtons($key),
                ];
            }
        }

        return view(
            'components.renderer.view-all.view-all-type-matrix-parent',
            [
                'matrixes' => $matrixes,

                'type' => $this->type,
                'filterRenderer' => $filterRenderer,
                'footer' => $footer,
                'rotate45Width' => $this->rotate45Width,
                'rotate45Height' => $this->rotate45Height,
                'groupBy' => $this->groupBy,
                'groupByLength' => $this->groupByLength,
                'tableTrueWidth' => $this->tableTrueWidth,
                'headerTop' => $this->headerTop,
                'tableTopCenterControl' => $this->tableTopCenterControl,
                'route' => $this->getRouteAfterSubmit(),
                'showLegend' => $this->showLegend,
                'maxH' => $this->maxH,
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
                                $agg_value += (1 * intval(str_replace(",", "", $cell->value)));
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
        $yAxis = $this->getYAxis();
        $yAxisTableName = (new $this->yAxis)->getTableName();
        $dataSource = $this->getMatrixDataSource($xAxis);
        $columns = [];
        $xAxis2ndHeading = [];
        if ($this->multipleMatrix) {
            // $matrixes = $this->getMultipleMatrixObjects();
            foreach (array_keys($this->matrixes) as $key) {
                // dump($xAxis[$key]);
                // dump($yAxis[$key]);
                // dump($dataSource[$key]);

                $dataSource[$key] = $this->mergeDataSource($xAxis[$key], $yAxis[$key], $yAxisTableName, $dataSource[$key], $forExcel, $key);
                $dataSource[$key] = $this->aggArrayOfCells($dataSource[$key]);
                $columns[$key] = $this->getColumns($xAxis[$key]);
                $xAxis2ndHeading[$key] = $this->getXAxis2ndHeader($xAxis[$key]);
            }
        } else {
            $dataSource = $this->mergeDataSource($xAxis, $yAxis, $yAxisTableName, $dataSource, $forExcel);
            $dataSource = $this->aggArrayOfCells($dataSource);
            $columns = $this->getColumns($xAxis);
            $xAxis2ndHeading = $this->getXAxis2ndHeader($xAxis);
        }
        // dump($dataSource);
        // dd();
        $result = [$yAxisTableName, $columns, $dataSource, $xAxis2ndHeading];
        // dump($result);
        return $result;
    }
}
