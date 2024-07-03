<?php

namespace App\Http\Controllers\Workflow;

class LibEditableTables extends AbstractLib
{
    protected static $key = "editable-tables";

    public static function getAllIndexed()
    {
        $all = parent::getAll();
        $result = [];
        foreach ($all as $value) {
            $checkbox = array_filter($value, fn ($v) => !is_null($v));
            $result['name'] = $value['name'];
            $result[$value['entity-type']][$value['editable-table']] = $checkbox;
        }
        return $result;
    }
}
