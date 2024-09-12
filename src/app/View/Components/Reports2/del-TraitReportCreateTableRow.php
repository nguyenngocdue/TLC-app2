<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\HrefReport;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait TraitReportCreateTableRow
{
    
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


}
