<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait TraitReportRowRendererType
{
    /* <Example Data>
        $rowConfigs = [
                "type"=> "status", // => id, link
                "entity_type"=> "hr_timesheet_officers", 
                "method"=> "edit", // route will refer to this one
                "route_id_field"=> "sheet_document"  // field to get value that will be triggered for route() 
            ]
    */
    public function makeValueForEachRow($rowData, $rowConfigs, $sourceField, $targetField){
        if (!is_array($rowData)) $rowData = (object)$rowData;
        $queriedValue = $rowData->$sourceField;
        if($rowConfigs && isset($rowConfigs['type'])) {
            $type = $rowConfigs['type'];
            switch ($type) {
                case 'status':
                    $statuses = isset($rowConfigs['entity_type']) ? LibStatuses::getFor($rowConfigs['entity_type']) : '';
                    $statusData = $statuses[$queriedValue] ?? [];
                    if($statusData) {
                        $content = Blade::render("<x-renderer.status>" .$queriedValue. "</x-renderer.status>");
                        $cellClass = 'text-' .$statusData['text_color'];
                        $href = route($rowConfigs['entity_type'].'.'.$rowConfigs['method'], $rowData->{$rowConfigs['route_id_field']}) ?? '';
                        $queriedValue = (object)[
                            'original_value' => $queriedValue,
                            'value' => $content,
                            'cell_class' => $cellClass ?? '',
                            'cell_href' => $href,
                        ];
                    }
                    break;
                default:
                    $queriedValue = $queriedValue;
                    break;
            }
        }
        $rowData->$targetField = $queriedValue;
        return $rowData;
   }

   public function makeStatusForEachRow($entityType, $targetValue, $content){
        $cellClass = '';
        $statuses = LibStatuses::getFor($entityType);
        $statusData = $statuses[$targetValue] ?? [];
        if($statusData) {
            $content = Blade::render("<x-renderer.status>" .$content. "</x-renderer.status>");
            $cellClass = 'text-' .$statusData['text_color'];
        }
        return [$content, $cellClass];
    }

    public function makeIdForEachRow($entityType, $targetValue, $content) {
        $content = $targetValue ? Str::makeId($targetValue) : $targetValue;
        $route = Str::plural($entityType) . ".edit";
        try {
            if (Route::has($route) && $targetValue) {
                $href = route($route, (int) $targetValue);
                $cellClass = 'text-blue-600';
            }
        } catch (\Exception $e) {
            $href = "#RouteNotFound3:$route";
            $cellClass = 'text-red-600';
        }
        return [$content, $cellClass, $href];
    }
}
