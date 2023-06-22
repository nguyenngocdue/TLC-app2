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
            }
        }
        // dump($array);
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
        $lib = LibPivotTables::getFor($this->modeType);
        $dataFields = $lib['data_fields'];
        $fields = $this->separateFields($lib['row_fields']);
        $rowFields = $fields['fields'] ?? [];
        $bidingFields = $fields['biding_fields'] ?? [];

        $dataIndex = array_values(array_map(function ($item) {
            return Str::singular($item['table_name']) . '_' . $item['attribute_name'];
        }, $bidingFields));

        $valueIndexFields = $lib['value_index_fields'];
        $filters = $lib['filters'];

        $fields = $this->separateFields($lib['column_fields']);
        $columnFields = $fields['fields'];
        $columnFields =   $this->mapValueIndexColumnFields($columnFields, $valueIndexFields);
        
        $bidingColumnFields = $fields['biding_fields'] ?? [];
        $dataAggregations = ReportPivot::combineArrays($dataFields, $lib['data_aggregations']);

        return [$rowFields, $bidingFields, $filters, $columnFields, $bidingColumnFields, $dataAggregations, $dataIndex];
    }
}
