<?php

namespace App\View\Components\Controls\RelationshipRenderer;

trait TraitTableColumnRO
{
    private function makeReadOnlyColumns($columns, $sp, $tableName, $noCss)
    {
        // dump($sp);
        // dump($columns);
        $result = [];
        foreach ($columns as $column) {
            if (isset($column['no_print']) && $column['no_print']) continue;
            $newColumn = $column;
            if (!isset($sp['props']["_" . $column['dataIndex']])) {
                dump("Column [" . $column['dataIndex'] . "] not found in SuperProps of " . $tableName . ". Pls double check your ManageProps screen.");
                $result[] = $newColumn;
                continue;
            }
            $prop = $sp['props']["_" . $column['dataIndex']];
            $newColumn['title'] = $column['title'] ?? $prop['label']; //. " <br/>" . $prop['control'];
            $newColumn['width'] = $prop['width'];
            switch ($prop['control']) {
                case 'id':
                    $newColumn['renderer'] = 'id';
                    $newColumn['type'] = $tableName;
                    $newColumn['align'] = 'center';
                    break;
                case 'dropdown':
                case 'radio':
                case 'dropdown_multi':
                case 'checkbox':
                case 'dropdown_multi_2a':
                case 'checkbox_2a':
                    $dataIndex = $prop['relationships']['control_name_function'];
                    $newColumn['dataIndex'] = $dataIndex;
                    $newColumn['renderer'] = $column['renderer'] ?? 'column';
                    $newColumn['rendererParam'] = $column['rendererParam'] ?? 'name';
                    break;
                case 'relationship_renderer':
                    $dataIndex = $prop['relationships']['control_name_function'];
                    $newColumn['dataIndex'] = $dataIndex;
                    $newColumn['renderer'] = 'agg_count';
                    $newColumn['rendererParam'] = $column['rendererParam'] ?? 'name';
                    break;
                case 'status':
                    $newColumn['renderer'] = 'status';
                    $newColumn['align'] = 'center';
                    break;
                case 'number':
                    $newColumn['align'] = 'right';
                    break;
                case 'toggle':
                    $newColumn['renderer'] = 'toggle';
                    $newColumn["align"] = "center";
                    break;
                case 'picker_datetime':
                    $newColumn['renderer'] = 'picker-datetime4';
                    $newColumn["align"] = "center";
                    break;
                case 'picker_date':
                    $newColumn['renderer'] = 'picker-date4';
                    $newColumn["align"] = "center";
                    break;
                case 'picker_time':
                    $newColumn['renderer'] = 'picker-time4';
                    $newColumn["align"] = "center";
                    break;
                case 'attachment':
                    $newColumn['renderer'] = "thumbnails";
                    break;
                default:
                    $newColumn['renderer'] = "text";
                    break;
            }
            $result[] = $newColumn;
        }
        // dump($result);
        return $result;
    }
}
