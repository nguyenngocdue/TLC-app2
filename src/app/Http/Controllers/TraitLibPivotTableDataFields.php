<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\ReportPivot;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

trait TraitLibPivotTableDataFields
{
    use CheckFieldsPivotInDatabase;
    private function separateFields($data)
    {
        $array = [];
        foreach ($data as $value) {
            if (str_contains($value, '(')) {
                $pos = strpos($value, '(');
                $field = substr($value, 0, $pos);
                $bindingName = substr($value,  $pos + 1, strlen($value) - $pos - 2);
                $array['fields'][] = $field;
                $array['biding_fields'][$field] =  array_combine(['table_name', 'attribute_name'], explode('.', $bindingName));
            } else {
                $array['fields'][] = $value;
                $array['biding_fields'][$value] =  [];
            }
        }
        // dd($array);
        return $array;
    }

    private function mapValueIndexColumnFields($_columnFields, $valueIndexFields)
    {
        $columnFields = [];
        foreach ($_columnFields as $key => $field) {
            $columnFields[] = [
                'fieldIndex' => $field,
                'valueIndexField' => $valueIndexFields[$key] ?? null,
                'aggregation' => 'sum'
            ];
        }
        // dd($columnFields);
        return $columnFields;
    }

    private function getDataIndex($row_fields){
        return array_values(array_map(function ($item) {
            if (str_contains($item, '(')) {
                [$posBracket, $posDot]  = [strpos($item, '('), strpos($item, '.',)];
                $name = Str::singular(substr($item, $posBracket + 1, $posDot - $posBracket - 1));
                $attr = substr($item, $posDot + 1, strlen($item) - $posDot - 2);
                return $name . '_' . $attr;
            }
            return $item;
        }, $row_fields));
    }

    private function sortByData($sortByColumn)
    {
        $orders = [];
        array_map(function ($item) use (&$orders) {
            $ex = explode(':', $item);
            $key = $ex[0];
            $strKey = trim(str_replace(['(', '.'], '_', $key), ')');
            $order = isset($ex[1]) ? strtolower($ex[1]) : 'asc';
            $orders[$strKey] = $order;
        }, $sortByColumn);
        // dd($orders);
        return $orders;
    }

    private function getFieldsInSortBy($data) {
        if (!$data) return [];
        $arrayFields = ReportPivot::separateValueByStringInArray($data, '(');
        return $arrayFields;
    }

    private function getFieldsToCheckDatabase($lib, $array1) {
        $keysToRemove = ['name', 'data_aggregations', 'lookup_tables'];
        foreach ($keysToRemove as $key) unset($lib[$key]);
        $fieldInSortBy = $this->getFieldsInSortBy($lib['sort_by']);
        foreach ($lib as $key => &$items){
            if ($key === 'row_fields') $items = $array1;
            if ($key === 'sort_by') $items = $fieldInSortBy;
            
        }
        return $lib;
    }

    private static function removeEmptyElements($array) {
        foreach ($array as $key => $items) {
            if ($items === "") continue;
            if (is_array($items)) {
                if (!$items) continue;
                $arr = array_filter($items, function($value) {
                    return $value !== "";
                });
                $array[$key] = $arr;
            }
        }
        return $array;
    }

    public function getDataFields()
    {
        $lib = LibPivotTables::getFor($this->key);
        $lib = self::removeEmptyElements($lib);
        if (!$this->checkCreateManagePivotReport($lib)) return false;

        $filters = $lib['filters'] ?? [];
        $row_fields = $lib['row_fields'] ?? [];
        $fields = $this->separateFields($row_fields);
        
        $bidingRowFields = $fields['biding_fields'] ?? [];
        $fieldsToCheckDatabase = $this->getFieldsToCheckDatabase($lib, array_keys($bidingRowFields));
        
        $primaryData = $this->dataSource;
        $isCheckFieldsInDatabase = $this->checkFieldsInDatabase($fieldsToCheckDatabase, $primaryData);
        if(!$isCheckFieldsInDatabase) return false;
        
        $rowFields = $fields['fields'] ?? [];
        $columnFields = $lib['column_fields'] ?? [];
        $isCheckRowAndColumnFields = $this->checkRowAndColumnFields($columnFields, $rowFields);
        if(!$isCheckRowAndColumnFields) return false;

        $fields = $this->separateFields($columnFields);
        $_columnFields = $fields['fields'] ?? [];
        $valueIndexFields = $lib['value_index_fields'] ?? [];


        $dataAggregation = $lib['data_aggregations'] ?? [];
        $propsColumnField = $this->mapValueIndexColumnFields($_columnFields, $valueIndexFields);
    
        $bidingColumnFields = $fields['biding_fields'] ?? [];

    
        $dataFields = $lib['data_fields'] ?? [];
        $dataAggregations = ReportPivot::combineArrays($dataFields, $dataAggregation);
    
        $sortBy = $lib['sort_by'] ?? [];
        $dataIndex = $this->getDataIndex($row_fields);
        return [$rowFields, $bidingRowFields, $filters, $propsColumnField, $bidingColumnFields, $dataAggregations, $dataIndex, $sortBy, $valueIndexFields, $columnFields];
    }
    
}
