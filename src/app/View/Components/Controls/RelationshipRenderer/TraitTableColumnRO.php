<?php

namespace App\View\Components\Controls\RelationshipRenderer;

trait TraitTableColumnRO
{
    private function makeReadOnlyColumns($columns, $sp, $tableName)
    {
        // dump($sp);
        $result = [];
        foreach ($columns as $column) {
            $newColumn = $column;
            if (!isset($sp['props']["_" . $column['dataIndex']])) {
                dd("Column [" . $column['dataIndex'] . "] not found in SuperProps of " . $tableName);
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
                case 'dropdown_multi':
                case 'radio':
                case 'checkbox':
                    $dataIndex = $prop['relationships']['control_name_function'];
                    $newColumn['dataIndex'] = $dataIndex;
                    $newColumn['renderer'] = 'column';
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
