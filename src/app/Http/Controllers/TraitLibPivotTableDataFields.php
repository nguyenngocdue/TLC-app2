<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\ReportPivot;
use Illuminate\Support\Str;

trait TraitLibPivotTableDataFields
{
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

    private function checkCreateManagePivotReport($lib)
    {
        if (count($lib) !== 2) return true;
        return false;
    }
    private function checkRowAndColumnFields($columnFields, $rowFields ) {
        $intersectRowCols = array_intersect($columnFields, $rowFields);
        if (count($intersectRowCols) > 0) {
            $str = implode(', ', $intersectRowCols);
            return $str;
        }
        return '';
    }


    public function getDataFields()
    {
        $lib = LibPivotTables::getFor($this->key);
        if (!$this->checkCreateManagePivotReport($lib)) {
            dd('Please update the Pivot Report Management feature.');
        }

        $filters = $lib['filters'] ?? [];
        $row_fields = $lib['row_fields'] ?? [];
        $fields = $this->separateFields($row_fields);
        $rowFields = $fields['fields'] ?? [];
        $bidingRowFields = $fields['biding_fields'] ?? [];
        
    
        $dataIndex = array_values(array_map(function ($item) {
            if (str_contains($item, '(')) {
                [$posBracket, $posDot]  = [strpos($item, '('), strpos($item, '.',)];
                $name = Str::singular(substr($item, $posBracket + 1, $posDot - $posBracket - 1));
                $attr = substr($item, $posDot + 1, strlen($item) - $posDot - 2);
                return $name . '_' . $attr;
            }
            return $item;
        }, $row_fields));
    
        $columnFields = $lib['column_fields'] ?? [];
        $invalidFields = $this->checkRowAndColumnFields($columnFields, $rowFields);
        if ($invalidFields) {
            dd("Please delete these fields [{$invalidFields}] in either the RowField or ColumnField column.");
        }
    
        $fields = $this->separateFields($columnFields);
        $columnFields = $fields['fields'] ?? [];
        $valueIndexFields = $lib['value_index_fields'] ?? [];
        $dataAggregation = $lib['data_aggregations'] ?? [];
        $propsColumnField = $this->mapValueIndexColumnFields($columnFields, $valueIndexFields);
    
        $bidingColumnFields = $fields['biding_fields'] ?? [];
        // dd($columnFields);
    
        $dataFields = $lib['data_fields'] ?? [];
        $dataAggregations = ReportPivot::combineArrays($dataFields, $dataAggregation);
        // dd($dataFields, $dataAggregations);
    
        $sortBy = $lib['sort_by'] ?? [];
        return [$rowFields, $bidingRowFields, $filters, $propsColumnField, $bidingColumnFields, $dataAggregations, $dataIndex, $sortBy, $valueIndexFields, $columnFields];
    }
    
}
