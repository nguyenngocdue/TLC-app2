<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibPivotTables;
use Illuminate\Support\Facades\Blade;

trait CheckFieldsPivotInDatabase
{

    public function checkFieldsInDatabase($lib, $dataSource)
    {
        if(is_object($dataSource)) $dataSource = (array)$dataSource->toArray()[0];
        $originalKeys = array_keys($dataSource);
        $failedFields = [];
        unset($lib['filters']);
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
        foreach ($failedFields as $key => $values) {
            $key = ucwords(str_replace('_', ' ', $key));
            foreach ($values as $field) $alertStr .= '<li>' . '[' . $field . '] in ' . $key . '</li>';
        }
        if ($alertStr) {
            // $pageName = '<a class="underline" target="_blank" href=' . route('managePivotTables.store') . '>' . 'Manage Pivot Tables' . '</a>';
            echo Blade::render("<x-feedback.alert type='warning' message='These below fields of Manage Pivot Tables were not found in the database:<br/>$alertStr'></x-feedback.alert>");
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
            echo Blade::render("<x-feedback.alert type='warning' message='The number of Column Fields must be less than 4.'></x-feedback.alert>");
            return false;
        }
        return true;
    }
    public function checkIntersectColumnAndValueIndex($columnFields, $valueIndexFields)
    {
        $intersect = array_intersect($valueIndexFields, $columnFields);
        $strFields = implode(", ", $intersect);
        if (!empty($intersect)) {
            echo Blade::render("<x-feedback.alert type='warning' message='The value of [$strFields] in Value Index Fields cannot be same as the values in the Column Fields.'></x-feedback.alert>");
            return false;
        }
        return true;
    }
}
