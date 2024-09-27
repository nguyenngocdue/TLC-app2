<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;

trait TraitReportMatrixColumn
{
    use TraitReportRowRendererType;
    use TraitReportTableCell;
    
    private function fillMissingFields(&$value, $fields, $valueToSet) {
        $missingFields = array_diff($fields, array_keys($value));
        $value = array_merge($value, array_fill_keys($missingFields, $valueToSet));
    }

    private function filterValidValues($intersect) {
        return array_filter($intersect, function($value) {
            return !is_null($value) && $value !== "";
        });
    }
    // private function setCellValue(&$values, $dataIndex, $cellValue, $cellClass) {
    //     $values[$dataIndex] = (object)[
    //         // 'original_value' => $cellValue, // to export excel
    //         'value' => $cellValue,
    //         'cell_class' => $cellClass ?? '',
    //     ];
    //     // dump($values);
    // }

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
        $rowConfigs = $params['row_renderer'] ?? null;
        $transformedFields = [];
        foreach ($data as &$rowData) {
            if (Report::checkValueOfField($rowData, $column)) {
                $targetField = $rowData->$column;
                if (!in_array($targetField, $transformedFields)) $transformedFields[] = $targetField;
                // To display row's value from 'grouping_to_matrix'
                // if($rowConfigs && isset($rowConfigs['type']) && $rowConfigs['type'] == 'status') {
                    $rowData = $this->makeValueForEachRow($rowData, $rowConfigs, $cellValue, $targetField);
                // }
            }
        }

        $groupedByRow = Report::groupArrayByKey($data, $row);
        // dd($groupedByRow['TLCM00009']);
        $mergedData = array_map(fn($item) => array_merge(...$item), $groupedByRow);
        array_walk($mergedData, fn(&$value) => $this->fillMissingFields($value, $transformedFields, $valueToSet));
        // dd($data->first(), $customCols);
        if ($customCols) {
            foreach ($customCols as $col){
                $aggType = $col['footer_row'] ?? '';
                $dataIndex  = isset($col['data_index']) ? $col['data_index'] :'';
                try {
                    switch ($aggType) {
                        case 'agg_sum':
                            foreach ($mergedData as &$values) {
                                $total = 0;
                                
                                foreach ($transformedFields as $type) {
                                    if (!isset($values[$type])) $values[$type] = $valueToSet;
                                    else {
                                        $q = $values[$type];
                                        if (isset($q->value)) {
                                            $val = is_numeric($q->value) ? (float)$q->value : 0;
                                            $total += $val;
                                        }

                                    } 
                                }
                                $this->setCellValue($values, $dataIndex, $total, $col['cell_class']);
                                // $this->setCellValue($values, $dataIndex, $total, $col['cell_class']);
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
                                $num = count($filteredArray);
                                $this->setCellValue($values, $dataIndex, $num, $col['cell_class']);
                            }
                            break;
                        default:
                            break;
                    }
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
        }
        sort($transformedFields);
        return [array_values($mergedData), $transformedFields];
    }
    
}
