<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Workflow\LibStatuses;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

trait TraitUpdateBasicInfoDataSource
{

    private static function updateFieldsStatusAndValues($values, $fields, $attrib, $fieldsHref)
    {
        $values =  (array)$values;
        // dd($values);
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
                $cellClass = '';
                $editedValue = '';
                if ($attrib && in_array($field, $fieldsHref)) {
                    $typeRender = isset($attrib[$field]['renderer']) ? $attrib[$field]['renderer'] : '';
                    $editedValue = $typeRender === 'id' ? '#000.' . $values[$field] : '';
                    $info = $attrib[$field];
                    $id = $values[str_replace('name', 'id', $field)];
                    // set cellHref attribute
                    if (isset($info['route_name'])) {
                        $cellHref = route($info['route_name'], $id);
                    } else {
                        if (isset($info['route_name_reference']) && isset($info['method'])) {
                            $cellHref = route(Str::plural($values[$info['route_name_reference']]) . '.' . $info['method'], $id);
                        } else {
                            $cellHref = "";
                        }
                    }
                    $cellClass = $cellHref ? 'text-blue-500' : '';
                }
                // dump($values, $editedValue, $field);
                if (!is_object($values[$field])) {
                    $values[$field] = (object)[
                        'value' => $editedValue ? $editedValue : $values[$field],
                        'cell_title' => $cellTitle,
                        'cell_href' => $cellHref,
                        'cell_class' => $cellClass,
                    ];
                }
            }
        }
        // dd($values);
        return $values;
    }

    protected function addTooltip($dataSource, $fieldInputs = [])
    {
        $dataHref = $this->getDisplayValueColumns();
        $fieldsHref = $dataHref ? array_values(...array_map(fn ($item) => array_keys($item), $dataHref)) : [];
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
            // 'source_name_status',

            'ghg_sheet_name',
            'ghg_metric_type_name',
            'ghg_metric_type_1_name',
            'ghg_metric_type_2_name',

            'kanban_task_bucket_name',
            'kanban_task_cluster_name',
            'kanban_task_pages_name',
            'kanban_task_name',
            'kanban_task_group_name',

            'esg_sheet_name',
            'esg_tmpl_name',
            'esg_metric_type_name',

            'parent_type_id',
            'ncr_id',

            'sheet_name',
            'sheet_status',
            'sheet_id',
            'qaqc_insp_tmpl_name',
            'qaqc_insp_chklst_id',
            'qaqc_insp_tmpl_id',
            'status_chklst_sht',


        ] + $fieldInputs;
        $attrib = [];
        foreach ($dataSource as $key => &$values) {
            if (isset($dataHref[$key])) $attrib = $dataHref[$key];
            if (($values instanceof Collection)) {
                foreach ($values as $k => &$item) {
                    $item = self::updateFieldsStatusAndValues($item, $fields, $attrib, $fieldsHref);
                    $values[$k] = $item;
                }
            } else {
                $values = self::updateFieldsStatusAndValues($values, $fields, $attrib, $fieldsHref);
            }
            $dataSource[$key] = $values;
        }
        return $dataSource;
    }

    public function getNamesOfModels($params, $keyNames)
    {
        $basicInfoData = [];
        foreach ($keyNames as $value) {
            $modelPath = "App\Models\\" . ucwords($value);
            $nameId = $value . '_id';
            $names = isset($params[$nameId]) ?
                implode(', ', $modelPath::find((array)$params[$nameId])
                    ->pluck('name')
                    ->toArray()) : "";
            $basicInfoData[$value . "_name"] = $names;
        }
        return $basicInfoData;
    }
}
