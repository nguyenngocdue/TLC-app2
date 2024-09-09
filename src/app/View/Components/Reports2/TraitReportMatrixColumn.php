<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;

trait TraitReportMatrixColumn
{
    private function createMatrix($data, $column, $row, $cellValue, $valueToSet = null) {
        $fields = [];
    
        foreach ($data as $key => $record) {
            $record = (array)$record;
            
            if (Report::checkValueOfField($record, $column)) {
                $field = $record[$column];
                if (!in_array($field, $fields)) $fields[] = $field;
                $record[$field] = $record[$cellValue];
            }
    
            $record = $this->filterAndRenameRow($record, $fields, $row);
            $data[$key] = (object)$record;
        }
    
        $groupedByRow = Report::groupArrayByKey($data, $row);
        $mergedData = array_map(fn($item) => array_merge(...$item), $groupedByRow);
    
        array_walk($mergedData, fn(&$value) => $this->fillMissingFields($value, $fields, $valueToSet));
    
        return array_values($mergedData);
    }
    
    private function filterAndRenameRow($record, $fields, $row) {
        $irrelevantFields = array_diff(array_keys($record), array_values($fields), [$row]);
        $filteredRecord = array_diff_key($record, array_flip($irrelevantFields));
        return $filteredRecord;
    }
    
    private function fillMissingFields(&$value, $fields, $valueToSet) {
        $missingFields = array_diff($fields, array_keys($value));
        $value = array_merge($value, array_fill_keys($missingFields, $valueToSet));
    }
}
