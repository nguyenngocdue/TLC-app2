<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Collection;

trait TraitShowTooltipForLines
{

    private static function addValues($values, $fields, $attrib)
    {
        $values =  (array)$values;
        $fields = array_intersect($fields, array_keys((array)$values));
        foreach ($fields as $field) {
            if (isset($values[$field])) {
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
        // dump($dataSet);
        $fields = [
            'user_name',
            'project_name',
            'sub_project_name',
            'prod_order_name',
            'prod_routing_name',
            'prod_routing_link_name',
            'department_name',
            'ecos_name',
        ] + $fieldInputs;
        $attrib = [];
        foreach ($dataSource as $key => &$values) {
            if (isset($dataSet[$key])) $attrib = $dataSet[$key];
            if (($values instanceof Collection)) {
                foreach ($values as $k => &$item) {
                    $item = self::addValues($item, $fields, $attrib);
                    $values[$k] = $item;
                }
            } else {
                $values = self::addValues($values, $fields, $attrib);
            }
            $dataSource[$key] = $values;
        }
        return $dataSource;
    }
}
