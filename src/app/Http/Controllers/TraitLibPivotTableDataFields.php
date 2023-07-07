<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\PivotReport;
use App\Utils\Support\StringPivotTable;
use Illuminate\Database\Eloquent\Relations\Pivot;
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




    private static function separateFields2($data, $patternFields, $valueIndexFields)
    {
        $array = [];
        foreach ($data as $key => $value) {
            $stringExplore = explode('.', StringPivotTable::stringByPattern($value, '/\((.*?)\)/'));
            $pos = StringPivotTable::findCharacterIndex($value, ['(', '{']);
            $filedKey = $pos > 0 ? substr($value, 0, $pos) : $value;

            $tableName = $stringExplore[0] ?? "";
            $attributeName = $stringExplore[1] ?? "";
            $titleOverride = ($t = StringPivotTable::stringByPattern($value, '/\{([^}]*)\}/')) ? $t : ucwords(str_replace('_', ' ', $patternFields[$key]));
            $valueIndexField = $valueIndexFields[$key] ?? "";
            $array[$patternFields[$key]] = [
                'table_name' => $tableName,
                'attribute_name' => $attributeName,
                'title_override' => $titleOverride,
                'field_index' => $filedKey,
                'value_field_index' => $valueIndexFields[$key] ?? "",
                'map_value_index_field' => $patternFields[$key].'_'.$valueIndexField,
            ];
        }
        return $array;
    }

    private function getDataIndex($row_fields)
    {
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

    private function getFieldsInSortBy($data)
    {
        if (!$data) return [];
        $arrayFields = PivotReport::separateValueByStringInArray($data, '(');
        return $arrayFields;
    }

    private function getFieldsToCheckDatabase($lib, $array1, $array2)
    {
        $keysToRemove = ['name', 'data_aggregations', 'lookup_tables'];
        foreach ($keysToRemove as $key) unset($lib[$key]);
        $fieldInSortBy = $this->getFieldsInSortBy($lib['sort_by'] ?? []);
        foreach ($lib as $key => &$items) {
            if ($key === 'row_fields') $items = $array1;
            if ($key === 'sort_by') $items = $fieldInSortBy;
            if ($key === 'column_fields') $items = $array2;
        }
        return $lib;
    }

    private static function removeEmptyElements($array)
    {
        foreach ($array as $key => $items) {
            if ($items === "") continue;
            if (is_array($items)) {
                if (!$items) continue;
                $arr = array_filter($items, function ($value) {
                    return $value !== "";
                });
                $array[$key] = $arr;
            }
        }
        return $array;
    }

    private static function getFieldsInColumnField($data)
    {
        $fields =  array_map(function($item)  {
            $pattern = ['(', ')', '.', '{', '}'];
            $pos = StringPivotTable::findCharacterIndex($item, ['(', '{']);
            return $pos >= 0 ? trim(substr($item, 0, $pos)) : $item;
        }, $data);
        return $fields;
    }

    public static function exploreColumnFields($columnFields, $valueIndexFields) {
        $originalColumnFields = self::getFieldsInColumnField($columnFields);
        $dupFieldInColumnFields = PivotReport::markDuplicates($originalColumnFields);
        $biddingField = self::separateFields2($columnFields, $dupFieldInColumnFields, $valueIndexFields);
        // dd($biddingField);






        return [
            'original_fields' => $originalColumnFields, 
            'duplicate_fields' => $dupFieldInColumnFields,
            'bidding_fields' => $biddingField
        ];
    }

    public function getDataFields()
    {
        $lib = LibPivotTables::getFor($this->key);
        $lib = self::removeEmptyElements($lib);
        if (!$this->checkCreateManagePivotReport($lib)) return false;

        $filters = $lib['filters'] ?? [];
        $row_fields = $lib['row_fields'] ?? [];


        $fields = $this->separateFields($row_fields);

        $columnFields = $lib['column_fields'] ?? [];
        $valueIndexFields = $lib['value_index_fields'] ?? [];

        $checkIntersectColumnAndValueIndex = $this->checkIntersectColumnAndValueIndex($columnFields, $valueIndexFields);
        if (!$checkIntersectColumnAndValueIndex) return false;

        $checkEmptyRowFieldsAndColumnFields = $this->checkEmptyRowFieldsAndColumnFields($row_fields, $columnFields);
        if (!$checkEmptyRowFieldsAndColumnFields) return false;

        
        $infoColumnFields = $this->exploreColumnFields($columnFields, $valueIndexFields);
        $originalFields = $infoColumnFields['original_fields'];
        $bidingColumnFields = $infoColumnFields['bidding_fields'] ?? [];

        $bidingRowFields = $fields['bidding_fields'] ?? [];

        $fieldsToCheckDatabase = $this->getFieldsToCheckDatabase($lib, array_keys($bidingRowFields), $originalFields);
        $primaryData = $this->dataSource;
        $checkFieldsInDatabase = $this->checkFieldsInDatabase($fieldsToCheckDatabase, $primaryData);
        if (!$checkFieldsInDatabase) return false;

        $rowFields = $fields['fields'] ?? [];
        $checkRowAndColumnFields = $this->checkRowAndColumnFields($columnFields, $rowFields);
        if (!$checkRowAndColumnFields) return false;

        $propsColumnField = $infoColumnFields['bidding_fields'];

        $dataFields = $lib['data_fields'] ?? [];
        $dataAggregation = $lib['data_aggregations'] ?? [];
        $dataAggregations = PivotReport::combineArrays($dataFields, $dataAggregation);

        $sortBy = $lib['sort_by'] ?? [];
        $dataIndex = $this->getDataIndex($row_fields);
        // dd($propsColumnField, $originalFields);
        $columnFields = $originalFields;

        return [$rowFields, $bidingRowFields, $filters, $propsColumnField, $bidingColumnFields, $dataAggregations, $dataIndex, $sortBy, $valueIndexFields, $columnFields, $infoColumnFields];
    }
}
