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

    private function mapValueIndexColumnFields ($_columnFields,$valueIndexFields){
        $columnFields = [];
        foreach ($_columnFields as $key => $field) {
            $columnFields[] = [
                'fieldIndex' => $field,
                'valueIndexField' => $valueIndexFields[$key] ?? dd('Empty Value Index Fields')
            ];
        }
        return $columnFields;
    }

    
    public function getDataFields()
    {
        $lib = LibPivotTables::getFor($this->key);
        $filters = $lib['filters'] ?? [];
        $row_fields = $lib['row_fields'] ?? [];
        $fields = $this->separateFields($row_fields);
        $rowFields = $fields['fields'] ?? [];
        $bidingRowFields = $fields['biding_fields'] ?? [];

        $dataIndex = array_values(array_map(function ($item) {
            // dd($item);
            if (str_contains($item, '(')) {
                [$posBracket,$posDot]  = [strpos($item, '('), strpos($item, '.', )];
                $name = Str::singular(substr($item,$posBracket+1, $posDot - $posBracket - 1));
                $attr = substr($item, $posDot+1, strlen($item)- $posDot - 2);
                return $name .'_'.$attr;
            }
            return $item;
        }, $row_fields));

        $columnFields = $lib['column_fields'] ?? [];
        $fields = $this->separateFields($columnFields);
        $columnFields = $fields['fields'] ?? [];
        $valueIndexFields = $lib['value_index_fields'] ?? [];
        $columnFields =   $this->mapValueIndexColumnFields($columnFields, $valueIndexFields);
        
        $bidingColumnFields = $fields['biding_fields'] ?? [];
        
        $dataFields = $lib['data_fields'] ?? [];
        $dataAggregation = $lib['data_aggregations'] ?? [];
        $dataAggregations = ReportPivot::combineArrays($dataFields,$dataAggregation );
        
        $sortBy = $lib['sort_by'] ?? [];
        return [$rowFields, $bidingRowFields, $filters, $columnFields, $bidingColumnFields, $dataAggregations, $dataIndex, $sortBy, $valueIndexFields];
    }
}
