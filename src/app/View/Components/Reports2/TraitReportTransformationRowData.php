<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\DateFormat;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait TraitReportTransformationRowData
{
    use TraitReportTableCell;

    public function changedAttrCellsByTransferData($rowData, $rowConfigs, $cellValue)
    {
        $type = $rowConfigs['type'];
        $queriedValue = $rowData->$cellValue;
        $cellClass = isset($configs['cell_class']) ? $configs['cell_class'] : '';
        switch ($type) {
            case 'status':
                $statuses = isset($rowConfigs['entity_type']) ? LibStatuses::getFor($rowConfigs['entity_type']) : '';
                $statusData = $statuses[$queriedValue] ?? [];
                if ($statusData) {
                    $content = Blade::render("<x-renderer.status>" . $queriedValue . "</x-renderer.status>");
                    $cellClass = 'text-' . $statusData['text_color'];
                    $href = route($rowConfigs['entity_type'] . '.' . $rowConfigs['method'], $rowData->{$rowConfigs['route_id_field']}) ?? '';
                    $queriedValue = $this->makeCellValue($queriedValue, $queriedValue, $content, $cellClass, $href);
                }
                break;
            case 'datetime':
                $dateTimeValue = DateFormat::getValueDatetimeByCurrentUser($queriedValue);
                $queriedValue = $this->makeCellValue($dateTimeValue, $dateTimeValue, $dateTimeValue, $cellClass);
                break;
            default:
                $queriedValue = $this->makeCellValue($queriedValue, $queriedValue, $queriedValue, $cellClass);
                break;
        }
        return $queriedValue;
    }


    public function makeValueForEachRow($configs, $rowData, $rowConfigs, $cellValue, $column){
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
        if($rowConfigs && isset($rowConfigs['type'])) {
            $queriedValue = $this->changedAttrCellsByTransferData($rowData, $rowConfigs, $cellValue);
        } else {
            $queriedValue = $this->makeCellValue($queriedValue,$queriedValue, $queriedValue,($configs['cell_class'] ?? ''));
        }
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
