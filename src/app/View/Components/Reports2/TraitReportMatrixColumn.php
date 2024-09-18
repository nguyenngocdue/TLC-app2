<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;

trait TraitReportMatrixColumn
{
    private function fillMissingFields(&$value, $fields, $valueToSet) {
        $missingFields = array_diff($fields, array_keys($value));
        $value = array_merge($value, array_fill_keys($missingFields, $valueToSet));
    }

    private function filterValidValues($intersect) {
        return array_filter($intersect, function($value) {
            return !is_null($value) && $value !== "";
        });
    }
    private function setCellValue(&$values, $dataIndex, $cellValue, $cellClass) {
        $values[$dataIndex] = (object)[
            'value' => $cellValue,
            'cell_class' => $cellClass ?? '',
        ];
    }

    private function getIntersectingValues($values, $transformedFields) {
        return array_filter($values, function($key) use ($transformedFields) {
            return in_array($key, $transformedFields);
        }, ARRAY_FILTER_USE_KEY);
    }
    
    
    

    private function createMatrix($data, $params, $customCols) {
        $column = $params['columns'];
        $row = $params['row'];
        $cellValue = $params['cell_value'];
        $valueToSet = $params['empty_value'] ?? null;
        $transformedFields = [];
        foreach ($data as $key => $record) {
            $record = (array)$record;
            
            if (Report::checkValueOfField($record, $column)) {
                $field = $record[$column];
                if (!in_array($field, $transformedFields)) $transformedFields[] = $field;
                $record[$field] = $record[$cellValue];
            }
            $data[$key] = (object)$record;
        }
        
        $groupedByRow = Report::groupArrayByKey($data, $row);
        $mergedData = array_map(fn($item) => array_merge(...$item), $groupedByRow);
        array_walk($mergedData, fn(&$value) => $this->fillMissingFields($value, $transformedFields, $valueToSet));
        

        foreach ($customCols as $key => $col){
            $aggType = $col['footer_row'] ?? '';
            $dataIndex  = isset($col['data_index']) ? $col['data_index'] :'';
            switch ($aggType) {
                case 'agg_sum':
                    foreach ($mergedData as &$values) {
                        $total = 0;
                        foreach ($transformedFields as $type) {
                            if (!isset($values[$type])) $values[$type] = $valueToSet;
                            else $total += (float)$values[$type];
                        }
                        $this->setCellValue($values, $dataIndex, $total, $col['cell_class']);
                    }
                    break;
                case "agg_count_unique_values":
                    foreach ($mergedData as &$values) {
                        $intersect = $this->getIntersectingValues($values, $transformedFields);
                        $filteredArray = $this->filterValidValues($intersect);
                        $uniqueValues = array_unique(array_values($filteredArray));
                        $count = count($uniqueValues);
                        $this->setCellValue($values, $dataIndex, $count, $col['cell_class']);
                    }
                    break;
                case "agg_count_all":
                    foreach ($mergedData as &$values) {
                        $intersect = $this->getIntersectingValues($values, $transformedFields);
                        $filteredArray = $this->filterValidValues($intersect);
                        $count = count($filteredArray);
                        $this->setCellValue($values, $dataIndex, $count, $col['cell_class']);
                    }
                    break;
                default:
                    break;
            }
        }
        sort($transformedFields);
        return [array_values($mergedData), $transformedFields];
    }
    
}
