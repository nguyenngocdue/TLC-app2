<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Collection;

trait TraitShowTooltipForLines
{

    private static function addValues($values, $fields)
    {
        $values =  (array)$values;
        foreach ($fields as $field){
            if (isset($values[$field])) {
                $f = str_replace('name', 'desc', $field);
                $cellTitle = isset($values[$f]) && !is_null($values[$f]) ? $values[$f] : 'Id: ' . $values[str_replace('name', 'id', $field)];
                $values[$field] = (object)[
                    'value' => $values[$field],
                    'cell_title' => $cellTitle,
                ];
            }
        }
        return $values;
    }

    protected function addTooltip($dataSource, $fieldInputs = [])
    {
        $fields = [
            'project_name',
            'sub_project_name',
            'prod_order_name',
            'prod_routing_name',
            'prod_routing_link_name',
        ] + $fieldInputs;
        foreach ($dataSource as $key => &$values) {
            if (($values instanceof Collection)) {
                foreach ($values as $k => &$item) {
                    $item = self::addValues($item, $fields);
                    $values[$k] = $item;
                }
            } else {
                $values = self::addValues($values, $fields);
            }
            $dataSource[$key] = $values;
        }
        return $dataSource;
    }
}
