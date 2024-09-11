<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Rp_report;
use Illuminate\View\Component;
use App\Utils\Support\HrefReport;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

class ReportBlockTable extends Component
{
    use TraitReportDataAndColumn;
    use TraitReportTermNames;


    protected $STATUS_ROW_RENDERER_ID = 661;
    protected $ID_ROW_RENDERER_ID = 662;
    protected $EXPORT_TYPE_ID = 621;
    protected $PAGINATION_TYPE_ID = 622;

    protected $reportType2 = 'report2';
    public function __construct(
        private $reportId,
        private $block,
        private $tableDataSource,
        private $headerCols,
        private $secondHeaderCols,
        private $currentParams,
        private $queriedData,
    ) {}

    private function getConfiguredCols($columns, $dataIndexToRender = [])
    {
        $result = [];
        foreach ($columns as  $column) {
            if (in_array($column->data_index, $dataIndexToRender)) {
                $result[$column->data_index] = $column;
            }
        }
        return $result;
    }

    private function createContentInRowCell($value, $column)
    {
        if ($column->parent_id) {
            $column = $column->getParent;
        }
        $content = $this->createIconPosition($value, $column->row_icon, $column->row_icon_position);
        return $content;
    }


    private function createTableDataSourceForRows($queriedData, $configuredCols, $block)
    {
        foreach ($queriedData as $k1 => &$dataLine) {
            $re = (object)[];
            foreach ($dataLine as $k2 => $value) {
                if (array_key_exists($k2, $configuredCols)) {
                    $column = $configuredCols[$k2];
                    $href = HrefReport::createDataHrefForRow($column, $dataLine);
                    $content = $this->createContentInRowCell($value, $column);
                    
                    $cellClass = $column->row_cell_class;
                    $cellDivClass =  $column->row_cell_div_class;
                    
                    
                    if($column->row_renderer == $this->STATUS_ROW_RENDERER_ID) {
                        $statuses = LibStatuses::getFor($column->entity_type);
                        $statusData = $statuses[$value] ?? [];
                        if($statusData) {
                            $content = Blade::render("<x-renderer.status>" .$content. "</x-renderer.status>");
                            $cellClass = 'text-' .$statusData['text_color'];
                        }
                    }
                    if($column->row_renderer == $this->ID_ROW_RENDERER_ID) {
                        $entityType = $column->entity_type;
                        $content = Str::makeId($value);
                        $route = Str::plural($entityType) . ".edit";
                        $hasRoute = Route::has($route);
                        if ($hasRoute) {
                            if (!$value) continue;
                            $href = route($route, (int)$value);
                            $cellClass = 'text-blue-600';
                        } else {
                            $href = "#RouteNotFound3:$route";
                            $cellClass = 'text-red-600';
                        }
                    }
                    // dd($column);

                    // Log::info($content);
                    $newValue = (object)[
                        'value' => $content,
                        'cell_href' => $href,
                        'cell_class' => $cellClass,
                        'cell_div_class' => $cellDivClass,
                    ];
                    $re->$k2 = $newValue;
                }
                elseif ($block->is_transformed_data) {
                    $re->$k2 = $value;
                }
            }
            $dataLine = $re;
            $queriedData->put($k1, $dataLine);
        }
        // dd($queriedData);
        return $queriedData;
    }


    private function getControls($typeId, $queriedData, $configuredCols){
        $rp =Rp_report::find($this->reportId);
        $reportId = $rp->id;
        $entityType = $rp->entity_type;
        $entityType2 = $this->reportType2;
        $routeExportExcel = route('rp_exportExcel');
        $currentParams = $this->currentParams;
        $pageLimit = $currentParams['per_page'] ?? 10;
        switch ($typeId) {
            case $this->PAGINATION_TYPE_ID:
                return Blade::render("<x-reports2.per-page-report2 
                                                    entityType='{$entityType}' 
                                                    reportType2='{$entityType2}' 
                                                    reportId='{$reportId}' 
                                                    pageLimit='{$pageLimit}'
                                    />");
            case $this->EXPORT_TYPE_ID:
                return Blade::render('<x-reports2.utility-report2 
                                            :route="$route" 
                                            :queriedData="$queriedData"
                                            :configuredCols="$configuredCols"
                                            />', [
                    'route' => $routeExportExcel,
                    'queriedData' => $queriedData,
                    'configuredCols' => $configuredCols
                ]);
            default:
                break;
        }
    }

    private function createColsWhenNotFoundRenderType()
    {
        $defaultCols = [];
        $firstRow = $this->queriedData->first();
        $keys = is_array($firstRow) ? array_keys($firstRow) : array_keys((array) $firstRow);
        if ($keys) {
            $defaultCols = array_map(fn($item) => [
                'dataIndex' => $item,
            ], $keys);
        }
        return $defaultCols;
    }

    public function render()
    {
        $block = $this->block;
        $columns = $this->block->getLines->sortby('order_no');
        
        $dataIndexToRender = array_column($this->headerCols, 'dataIndex');
        $configuredCols = $this->getConfiguredCols($columns, $dataIndexToRender);
        
        $tableDataSource = $this->createTableDataSourceForRows($this->tableDataSource, $configuredCols, $block);
        $headerCols = $this->headerCols;
        // render default table
        if(!$configuredCols) {
            $tableDataSource = $this->queriedData;
            $headerCols = $this->createColsWhenNotFoundRenderType();
        } 

        return view('components.reports2.report-block-table', [
            'block' => $block,
            "name" => $block->name,
            "description" => $block->description,
            "tableDataSource" => $tableDataSource,
            "tableColumns" =>  $headerCols,
            "secondHeaderCols" => $this->secondHeaderCols,

            "showNo" => $block->showNo,
            "tableTrueWidth" => $block->table_true_width,
            "maxHeight" => $block->max_h,
            "rotate45Width" => $block->rotate_45_width,
            "rotate45Height" => $block->rotate_45_height,
            "hasPagination" => $block->has_pagination,

            "topLeftControl" => $this->getControls($block->top_left_control, $this->queriedData, $configuredCols),
            "topCenterControl" => $this->getControls($block->top_center_control, $this->queriedData, $configuredCols),
            "topRightControl" =>  $this->getControls($block->top_right_control, $this->queriedData, $configuredCols),
            "bottomLeftControl" => $this->getControls($block->bottom_left_control, $this->queriedData, $configuredCols),
            "bottomCenterControl" => $this->getControls($block->bottom_center_control, $this->queriedData, $configuredCols),
            "bottomRightControl" => $this->getControls($block->bottom_right_control, $this->queriedData, $configuredCols),
        ]);
    }
}
