<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\TraitLibPivotTableDataFields2;
use App\Http\Controllers\Workflow\LibPivotTables2;

trait TraitChangeDataPivotTable2
{
    use TraitLibPivotTableDataFields2;
    public function changeValueData($data)
    {
        if ($this->modeType) {
            $results = [];
            $libs = LibPivotTables2::getFor($this->modeType);
            $rowFields = $libs['row_fields'];
            $fieldsUnShowTitle = ['staff_id'];
            foreach ($data as $values) {
                foreach ($values as $key => $value) {
                    foreach ($rowFields as $keyField => $attrs) {
                        $indexField = $keyField;
                        if (isset($attrs->column)) {
                            $indexField .= '_' . str_replace('.', '_', $attrs->column);
                        }
                        if ($key === $indexField) {
                            $values[$key] = (object) [
                                'value' => $value,
                                'cell_title' =>  in_array($keyField, $fieldsUnShowTitle) ? '' : 'ID: ' . (string)$values[$keyField],
                            ];
                        }
                    }
                }
                $results[] = $values;
            }
            return collect($results);
        }
        return $data;
    }
}
