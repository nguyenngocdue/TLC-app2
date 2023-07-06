<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibPivotTables;
use Illuminate\Support\Facades\Blade;

trait CheckFieldsPivotInDatabase
{

    public function checkFieldsInDatabase($lib, $dataSource)
    {
        $originalKeys = array_keys(array_merge(...array_values($dataSource)));
        $failedFields = [];
        foreach ($lib as $key => $values) {
            if (empty($values)) continue;
            foreach ($values as $value) {
                if (empty($value)) continue;
                if (in_array($value, $originalKeys)) continue;
                else {
                    $failedFields[$key][] = $value;
                }
            }
        }
        $alertStr = '';
        $route = route('managePivotTables.store');
        foreach ($failedFields as $key => $values) {
            $key = ucwords(str_replace('_', ' ', $key));
            foreach ($values as $field) $alertStr .= '<a class="underline" target="_blank" href=' . $route . '>' . 'Field [' . $field . '] in ' . $key . ' column' . '</a>' . '</br>';
        }
        if ($alertStr) {
            $pageName = '<a class="underline" target="_blank" href=' . route('managePivotTables.store') . '>' . 'Manage Pivot Tables' . '</a>';
            echo Blade::render("<x-feedback.alert type='warning' message='These fields of $pageName were not found in the database.<br/>$alertStr'></x-feedback.alert>");
            return false;
        }
        return true;
    }

    public function checkRowAndColumnFields($columnFields, $rowFields)
    {
        $intersectRowCols = array_intersect($columnFields, $rowFields);
        $str = '';
        if (count($intersectRowCols) > 0) $str = implode(', ', $intersectRowCols);
        if ($str) {
            $pageName = '<a class="underline" target="_blank" href=' . route('managePivotTables.store') . '>' . 'Manage Pivot Tables' . '</a>';
            echo Blade::render("<x-feedback.alert type='warning' message='Please delete these fields [{$str}] in either the RowField or ColumnField column at the {$pageName}.'></x-feedback.alert>");
            return false;
        }
        return true;
    }

    public function checkCreateManagePivotReport($lib)
    {
        if (count($lib) !== 2) return true;
        echo Blade::render("<x-feedback.alert type='warning' message='Please update the Pivot Report Management feature.'></x-feedback.alert>");
        return false;
    }

    public function checkEmptyRowFieldsAndColumnFields($rowFields, $columnFields)
    {
        if (empty($rowFields) && count($columnFields) > 3) {
            echo Blade::render("<x-feedback.alert type='warning' message='Please provide the number of Column_Field fields that are less than 4.'></x-feedback.alert>");
            return false;
        }
        return true;
    }
    public function checkIntersectColumnAndValueIndex($columnFields, $valueIndexFields){
        $intersect = array_intersect($valueIndexFields, $columnFields);
        $strFields = implode(", ", $intersect);
        if (!empty($intersect)) {
            echo Blade::render("<x-feedback.alert type='warning' message='Disallow these fields [$strFields] in Value_Index_Fields to be the same as the values in the Column_Fields column.'></x-feedback.alert>");
            return false;
        }
        return true;
    }
}
