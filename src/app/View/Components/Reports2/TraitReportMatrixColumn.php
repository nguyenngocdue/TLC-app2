<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;

trait TraitReportMatrixColumn
{
    private function fillMissingFields(&$value, $fields, $valueToSet) {
        $missingFields = array_diff($fields, array_keys($value));
        $value = array_merge($value, array_fill_keys($missingFields, $valueToSet));
    }

    private function createMatrix($data, $params, $hasTotalCols) {
        $column = $params['columns'];
        $row = $params['row'];
        $cellValue = $params['cell_value'];
        $valueToSet = $params['empty_value'] ?? null;
        $fieldOfTransformedCols = [];
        foreach ($data as $key => $record) {
            $record = (array)$record;
            
            if (Report::checkValueOfField($record, $column)) {
                $field = $record[$column];
                if (!in_array($field, $fieldOfTransformedCols)) $fieldOfTransformedCols[] = $field;
                $record[$field] = $record[$cellValue];
            }
            $data[$key] = (object)$record;
        }
        
        $groupedByRow = Report::groupArrayByKey($data, $row);
        $mergedData = array_map(fn($item) => array_merge(...$item), $groupedByRow);
        array_walk($mergedData, fn(&$value) => $this->fillMissingFields($value, $fieldOfTransformedCols, $valueToSet));
        
        if ($hasTotalCols) {
            foreach ($mergedData as &$values) {
                $total = 0;
                foreach ($fieldOfTransformedCols as $type) {
                    if (!isset($values[$type])) $values[$type] = 0;
                    else $total += (float)$values[$type];
                }
                $values['total'] = $total;
            }
        }
        return [array_values($mergedData), $fieldOfTransformedCols];
    }
    
}
