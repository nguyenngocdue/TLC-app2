<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Workflow\LibStatuses;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

trait TraitUpdateBasicInfoDataSource
{

    private static function updateFieldsStatusAndValues($values, $fields, $attrib)
    {
        $values =  (array)$values;
        // dd($fields, $values, $attrib);
        $fields = array_intersect($fields, array_keys((array)$values));
        foreach ($fields as $field) {
            if (isset($values[$field])) {
                if (str_contains($field, 'status')) {
                    $statusName = $values[$field];
                    if (is_array($statusName)) continue;
                    $libStatus = LibStatuses::getAll()[$statusName];
                    $values[$field] = (object) [
                        'value' => Blade::render("<x-renderer.status>" . $libStatus['name'] . "</x-renderer.status>"),
                        'cell_class' =>  'text-' . $libStatus['text_color'],
                    ];
                    continue;
                }
                $f = str_replace('name', 'desc', $field);
                $cellTitle = isset($values[$f]) && !is_null($values[$f]) ? $values[$f] : 'Id: ' . $values[str_replace('name', 'id', $field)];
                $cellHref = '';
                if ($attrib && isset($attrib[$field])) {
                    $info = $attrib[$field];
                    $id = $values[str_replace('name', 'id', $field)];
                    $cellHref =  isset($info['route_name']) ? route($info['route_name'], $id) : $cellHref;
                    $cellClass = $cellHref ? 'text-blue-800' : '';
                }
                $values[$field] = (object)[
                    'value' => $values[$field],
                    'cell_title' => $cellTitle,
                    'cell_href' => $cellHref,
                    'cell_class' => $cellClass ?? null,
                ];
            }
        }
        return $values;
    }

    protected function addTooltip($dataSource, $fieldInputs = [])
    {
        $dataSet = $this->getDisplayValueColumns();
        $fields = [
            'user_name',
            'project_name',
            'sub_project_name',
            'prod_order_name',
            'prod_routing_name',
            'prod_routing_link_name',
            'prod_discipline_name',
            'department_name',
            'ecos_name',
            'prod_sequence_status',
            'sub_project_status',
            'prod_order_status',
            'ncr_status',
            'ghg_sheet_name',
            'ghg_metric_type_name',
            'ghg_metric_type_1_name',
            'ghg_metric_type_2_name',

            'kanban_task_bucket_name',
            'kanban_task_cluster_name',

        ] + $fieldInputs;
        $attrib = [];
        foreach ($dataSource as $key => &$values) {
            // if($key === 'tableDataSource') {
            if (isset($dataSet[$key])) $attrib = $dataSet[$key];
            // dd($attrib);
            if (($values instanceof Collection)) {
                foreach ($values as $k => &$item) {
                    $item = self::updateFieldsStatusAndValues($item, $fields, $attrib);
                    $values[$k] = $item;
                }
            } else {
                $values = self::updateFieldsStatusAndValues($values, $fields, $attrib);
            }
            $dataSource[$key] = $values;

            // };

        }
        // dd($dataSet, $dataSource);
        return $dataSource;
    }
}
