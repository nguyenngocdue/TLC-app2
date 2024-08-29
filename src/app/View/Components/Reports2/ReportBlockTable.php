<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Workflow\LibStatuses;
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

    public function __construct(
        private $reportId,
        private $block,
        private $rawTableDataSource,
        private $headerCols,
        private $secondHeaderCols,
    ) {}

    private function getConfiguredColumns($columns, $dataIndexToRender = [])
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


    private function createTableDataSourceForRows($queriedData, $configuredColumns)
    {
  
        $result = collect();
        foreach ($queriedData as $k1 => $dataLine) {
            // dd($configuredColumns);
            $re = (object)[];
            foreach ($dataLine as $k2 => $value) {
                if (array_key_exists($k2, $configuredColumns)) {
                    $column = $configuredColumns[$k2];
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
                        $content = '#000.' .$value;
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

                    // Log::info($content);
                    $newValue = (object)[
                        'value' => $content,
                        'cell_href' => $href,
                        'cell_class' => $cellClass,
                        'cell_div_class' => $cellDivClass,
                    ];
                    $re->$k2 = $newValue;
                }
            }
            $result->put($k1, $re);
        }
        // dd($result);
        return $result;
    }


    public function render()
    {
        $block = $this->block;
        $columns = $this->block->getLines->sortby('order_no');

        $dataIndexToRender = array_column($this->headerCols, 'dataIndex');
        $configuredColumns = $this->getConfiguredColumns($columns, $dataIndexToRender);

        $newTableDataSource = $this->createTableDataSourceForRows($this->rawTableDataSource, $configuredColumns, $block);

        return view('components.reports2.report-block-table', [
            'block' => $block,
            "name" => $block->name,
            "description" => $block->description,
            "tableDataSource" => $newTableDataSource,
            "tableColumns" =>  $this->headerCols,
            "secondHeaderCols" => $this->secondHeaderCols,

            "showNo" => $block->showNo,
            "tableTrueWidth" => $block->table_true_width,
            "maxHeight" => $block->max_h,
            "rotate45Width" => $block->rotate_45_width,
            "rotate45Height" => $block->rotate_45_height,
            "hasPagination" => $block->has_pagination,

            "topLeftControl" => $block->top_left_control,
            "topCenterControl" => $block->top_center_control,
            "topRightControl" => $block->top_right_control,
            "bottomLeftControl" => $block->bottom_left_control,
            "bottomCenterControl" => $block->bottom_center_control,
            "bottomRightControl" => $block->bottom_right_control,
        ]);
    }
}
