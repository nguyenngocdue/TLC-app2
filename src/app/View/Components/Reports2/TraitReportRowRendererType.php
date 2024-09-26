<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Support\Facades\Blade;

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
    public function makeValueEachRow($rowData, $rowConfigs, $sourceField, $targetField){
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
}
