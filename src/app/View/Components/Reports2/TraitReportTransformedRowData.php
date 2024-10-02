<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\DateFormat;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait TraitReportTransformedRowData
{
    use TraitReportTableCell;

    private function getHref($rowConfigs, $rowData) {
        try {
            return route($rowConfigs['entity_type'] . '.' . $rowConfigs['method'], $rowData->{$rowConfigs['route_id_field']}) ?? '';
        } catch (\Exception $e) {
            return null;
        }
    }

    private function processStatusCell($rowConfigs, $queriedValue, $type, $href = null) {
        $statuses = isset($rowConfigs['entity_type']) ? LibStatuses::getFor($rowConfigs['entity_type']) : '';
        $statusData = $statuses[$queriedValue] ?? [];
        if ($statusData) {
            $cellTitle = 'Open this document (' . $statusData['title'] . ')';
    
            // Handle rendering based on the type (status or other)
            if ($type == 'status') {
                $cellClass = 'text-' . $statusData['text_color'];
                $content = Blade::render("<x-renderer.status>" . $queriedValue . "</x-renderer.status>");
            } else {
                $content = $statusData['icon'];
                $cellClass = 'bg-' . $statusData['bg_color'] . ' text-center';
                $cellDivClass = 'text-' . $statusData['text_color'];
            }
    
            // Return the formatted cell value
            return $this->makeCellValue($queriedValue, $queriedValue, $content, $cellClass, $href, $cellDivClass ?? '', $cellTitle);
        }
        return $queriedValue; 
    }


    public function changedAttrCellsByTransferData($rowData, $rowConfigs, $cellValue)
    {
        $type = $rowConfigs['type'];
        $queriedValue = $rowData->$cellValue;
        $cellClass = isset($rowConfigs['cell_class']) ? $rowConfigs['cell_class'] : '';
        $href = self::getHref($rowConfigs, $rowData);
        switch ($type) {
            case 'icon':
            case 'status':
                $queriedValue = $this->processStatusCell($rowConfigs, $queriedValue, $type, $href);
                break;
            case 'datetime':
                $formatType = isset($rowConfigs['format_datetime']) ? $rowConfigs['format_datetime'] : '';
                $dateTimeValue = DateFormat::getValueDatetimeByCurrentUser($queriedValue, $formatType);
                $queriedValue = $this->makeCellValue($dateTimeValue, $dateTimeValue, $dateTimeValue, $cellClass, $href);
                break;
               
            default:
                $queriedValue = $this->makeCellValue($queriedValue, $queriedValue, $queriedValue, $cellClass);
                break;
        }
        return $queriedValue;
    }


    public function makeValueForEachRow($configs, $rowData, $cellValue, $column){
        /* <Example Data>
            $rowConfigs = [
                    "type"=> "status", // => id, link
                    "entity_type"=> "hr_timesheet_officers", 
                    "method"=> "edit", // route will refer to this one
                    "route_id_field"=> "sheet_document"  // field to get value that will be triggered for route() 
                ]
        */
        if (!is_array($rowData)) $rowData = (object)$rowData;
        $queriedValue = $rowData->$cellValue;

        $queriedValue = isset($configs['params'], $configs['params']['row_renderer'],$configs['params']['row_renderer']['type']) 
        ? $this->changedAttrCellsByTransferData($rowData, $configs['params']['row_renderer'], $cellValue) 
        : $this->makeCellValue($queriedValue, $queriedValue, $queriedValue, $configs['cell_class'] ?? '');
    
    $rowData->$column = $queriedValue;
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
