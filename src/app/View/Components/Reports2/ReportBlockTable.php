<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_block;
use App\Models\Rp_report;
use App\Utils\Support\DateFormat;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Blade;

class ReportBlockTable extends Component
{
    use TraitReportQueriedData;
    use TraitReportTermNames;

    use TraitReportTableContent;

    protected $reportType2 = 'report2';
    public function __construct(
        private $reportId,
        private $block,
        private $tableDataSource,
        private $headerCols,
        private $secondHeaderCols,
        private $currentParams,
        private $currentFormattedParams,
        private $queriedData,
    ) {}

    private function getControls($typeId, $queriedData, $configuredCols, $class)
    {
        $rp = Rp_report::find(($reportId = $this->reportId));
        $routeRp = route('report_filters' . '.update', $reportId);
        $entityType = $rp->entity_type;
        $entityType2 = $this->reportType2;
        $routeExExcel = route('rp_exportExcel');
        $currentParams = $this->currentParams;
        $pageLimit = $currentParams['per_page'] ?? 10;
        $hasPagination = $this->block->has_pagination;
        switch ($typeId) {
            case $this->PAGINATION_TYPE_ID:
                if ($hasPagination) {
                    return Blade::render("<x-reports2.per-page-report2 
                                                        entityType='{$entityType}' 
                                                        reportType2='{$entityType2}' 
                                                        reportId='{$reportId}' 
                                                        pageLimit='{$pageLimit}'
                                                        route='{$routeRp}'
                                        />");
                }
                break;
            case $this->EXPORT_TYPE_ID:
                return Blade::render('<x-reports2.utility-report2 
                                            :route="$route" 
                                            :queriedData="$queriedData"
                                            :configuredCols="$configuredCols"
                                            :blockTitle="$blockTitle"
                                            :class="$class"
                                            />', [
                    'route' => $routeExExcel,
                    'queriedData' => $queriedData,
                    'configuredCols' => $configuredCols,
                    'blockTitle' => $this->block->title ?: $this->block->name,
                    'class' => $class,
                ]);
            case "":
                // There is no selected control
                break;
            default:
                dump("Unsupported control type [" . $typeId . "]");
                break;
        }
    }

    function updateTimezone($queriedData, $currentParams, $columns)
    {
        $datetimeCols = array_values(array_filter($columns->toArray(), fn($item) => $item['row_renderer'] === $this->ROW_RENDERER_DATETIME_ID));
        foreach ($queriedData as &$value) {
            if ($datetimeCols) {
                foreach ($datetimeCols as $datetimeCol) {
                    $dataIndex = isset($datetimeCol['data_index']) ?  $datetimeCol['data_index'] : null;
                    if ($dataIndex) {
                        $val = $value->{$dataIndex};
                        $updatedVal = DateFormat::getValueDatetimeByCurrentUser($val);
                        if (isset($datetimeCol['row_renderer_params'])) {
                            $rowParam = $datetimeCol['row_renderer_params'];
                            $updatedVal = DateFormat::formatDateTime($updatedVal, $rowParam, 'Y-m-d H:i:s');
                        }
                        $value->{$dataIndex} = $updatedVal;
                    }
                }
            }
        }
        return $queriedData;
    }

    public function getDynamicColumns($iteratorBlock, $originalLine, $currentParams)
    {
        $SqlStr = $iteratorBlock->sql_string;
        $SqlStr = $this->getSql($SqlStr, $currentParams);
        $data = $this->getDataSQLString($SqlStr);
        $cols = [];
        foreach ($data as $iterator) {
            $dataIndex = $iterator->dataIndex;
            $originalLine['data_index'] = $dataIndex;
            $arr = $originalLine->toArray();
            $cols[] = collect($arr);
        }
        return $cols;
    }

    public function getDeepColumns($columns, $currentParams) {
        $queriedCols = [];
        foreach($columns as $col) {
            $queriedCols[] = $col;
            if ($col['iterator_block_id']) {
                $iteratorBlock = Rp_block::find($col['iterator_block_id']);
                $cols = $this->getDynamicColumns($iteratorBlock, $col, $currentParams);
                $queriedCols = array_merge($queriedCols, $cols);
            }
        }
        return $queriedCols;
    }

    public function getTableSpanDataSource($data, $queriedData, $columns, $currentParams) {
        $columns = $this->getDeepColumns($columns, $currentParams);
        $tableSpanDataSource = [];
        foreach($queriedData as $key => $row) {
            $colSetting = isset($data[$key]) ? $data[$key] : null;
            $configs = [];
            foreach($columns as $col) {
                if (!$col['is_active']) continue;
                $dataIndex = $col['data_index'];
                if (!isset($row->{$dataIndex})) continue;
                $rowSpan = $dataIndex.'_row_span';
                $rowSpanValue = isset($row->{$rowSpan}) ? $row->{$rowSpan} : 1;
                $cellValue = $row->{$dataIndex};
                $href = $col['row_renderer_params'];

                // Create Link for dynamic columns
                if ($col["row_renderer"] == $this->ROW_RENDERER_LINK_ID) {
                    if (isset($row->{$dataIndex.'_link'}) && $href && $row->{$dataIndex.'_link'}) {
                        $cellDivClass = $col["cell_div_class"] ?? 'text-blue-600';
                        $currentParams['id'] = $row->{$dataIndex.'_link'};
                        $reportTableRow = ReportTableRow::getInstance();
                        // https://app2.tlcmodular.com/dashboard/ghg_sheets/{%id%}/edit
                        $href = $reportTableRow->createHref($href,$row, $currentParams);
                        $cellValue =
                        "<a class='{$cellDivClass}' href='{$href}' target='blank'>
                            <span>{$cellValue}</span>
                        </a>"; 
                    }
                }


                $att = array_merge([
                    'value' => $cellValue,
                    'rowspan' => $rowSpanValue,
                ], $this->makeRowValue($col));
                
                if ($colSetting && isset($colSetting->{$dataIndex})) {
                    $att = array_merge($att, (array)$colSetting->{$dataIndex});
                }
                $configs[$dataIndex] = (object)$att;
                // dd($row, $configs);
            }
            $tableSpanDataSource[] = $configs;
        }
        return $tableSpanDataSource;
    }

    public function render()
    {
     
        $block = $this->block;
        $columns = $this->block->getLines->sortby('order_no');
        $headerCols = $this->headerCols;
        $dataIndexToRender = array_column($headerCols, 'dataIndex');
        $queriedData = $this->queriedData;
        $currentParams = $this->currentParams;
        $queriedData = $this->updateTimezone($queriedData, $currentParams, $columns);
        
        $reportTableColumn = ReportTableColumn::getInstance();
        $configuredCols = $reportTableColumn->getConfiguredCols($columns, $dataIndexToRender);
        
        $reportTableRow = ReportTableRow::getInstance();
        $drawData = method_exists($this->tableDataSource, 'items') ? $this->tableDataSource->items() : $this->tableDataSource;
        $tableDataSource = $reportTableRow->createTableDataSourceForRows(
            $drawData,
            $configuredCols,
            $block,
            $currentParams
        );
        
        if ($block->has_pagination) {
            $tableDataSource = method_exists(($x = $this->tableDataSource), 'setCollection') ?  $x->setCollection($tableDataSource) : $x;
        }
        // Columns were not created 
        if (!$configuredCols) {
            $tableDataSource = $this->queriedData;
            // has pagination, columns were not created 
            if ($block->has_pagination) {
                $tableDataSource = $this->tableDataSource->setCollection(collect($drawData));
            }
            $headerCols = $reportTableColumn->createColsWhenNotFoundRenderType($this->queriedData);
        }
        
        //Transformed data option
        if ($block->is_transformed_data) {
            $configuredCols = $reportTableColumn->updateConfiguredCols($headerCols);
        }
        
        //Dynamic columns
        if ($columns->pluck('iterator_block_id')->filter()->toArray()) {
            $tableDataSource = $this->getTableSpanDataSource($tableDataSource, $queriedData, $columns, $currentParams);
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
            "headerTop" => $block->header_top,
            "legendEntityType" => $block->legend_entity_type ? $block->legend_entity_type : null,

            "topLeftControl" => $this->getControls($block->top_left_control, $queriedData, $configuredCols, "justify-start"),
            "topCenterControl" => $this->getControls($block->top_center_control, $queriedData, $configuredCols, "justify-center"),
            "topRightControl" =>  $this->getControls($block->top_right_control, $queriedData, $configuredCols, "justify-right"),
            "bottomLeftControl" => $this->getControls($block->bottom_left_control, $queriedData, $configuredCols, "justify-start"),
            "bottomCenterControl" => $this->getControls($block->bottom_center_control, $queriedData, $configuredCols, "justify-center"),
            "bottomRightControl" => $this->getControls($block->bottom_right_control, $queriedData, $configuredCols, "justify-right"),
        ]);
    }
}
