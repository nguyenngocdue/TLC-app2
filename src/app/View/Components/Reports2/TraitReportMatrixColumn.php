<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;

trait TraitReportMatrixColumn
{
    use TraitReportTransformedRowData;
    use TraitReportTableCell;

    private function fillMissingFields(&$value, $fields, $valueToSet)
    {
        $missingFields = array_diff($fields, array_keys($value));
        $value = array_merge($value, array_fill_keys($missingFields, $valueToSet));
    }

    private function filterValidValues($intersect)
    {
        return array_filter($intersect, function ($value) {
            return !is_null($value) && $value !== "";
        });
    }

    private function calculatePercentage($numerator, $denominator)
    {
        return ($denominator > 0) ? number_format(($numerator / $denominator) * 100, 2) : '0.00';
    }

    private function prepareContent($finishedCount, $total, $percent)
    {
        return "<span> {$finishedCount}/{$total} </br> {$percent}% </span>";
    }

    // Helper to calculate sum
    private function getSum(array $items)
    {
        return array_sum($items);
    }

    // Helper to check if all values are 'NA'
    private function isAllNA(array $items): bool
    {
        return array_unique($items) === ['NA'];
    }

    function processOtherColumns(array $cols, array &$progresses, array $config)
    {
        foreach ($cols as $key => $value) {
            // Calculate basic values
            $finishedCount = $this->getSum($value['item_finished'] ?? []);
            $total = $this->getSum($value['item_total'] ?? []);
            
            // Check if all values are 'NA'
            $isNA = $this->isAllNA($value['item_total'] ?? []);
            
            // Prepare content for display
            $percent = $this->calculatePercentage($finishedCount, $total);
            $content = $isNA
            ? '<span>NA</span>'
            : $this->prepareContent($finishedCount, $total, $percent);
            
            // Add CSS class if defined
            $cellClass = $config['cell_class'] ?? '';

            // Update cell value for transformed data columns
            $this->setCellValue($progresses, $key, $content, $content, $cellClass);
        }
    }


    function getAdvancedAggColConfig($addedCols, &$progresses, $config)
    {
        foreach ($addedCols as $key => $value) {
            $total = isset($value['total']) ? $value['total'] : 0;
            $countedItem = isset($value['counted']) ? $value['counted'] : 0; // Default to 1 to avoid division by zero

            $percent = ($countedItem > 0) ? number_format($total / $countedItem, 2) : '0.00';
            $content = "<strong>{$percent}%</strong>";
            $cellClass = $config['cell_class'] ?? '';

            // Set the cell value for non-transformed columns
            $this->setCellValue($progresses, $key, $percent, $content, $cellClass);
        }
    }

    private function makeAggProgressRows($mergedData, $transformedFields, $col)
    {
        $assignedParts = isset($col['progress_row_config']) ? $col['progress_row_config'] : null;
        if (!$assignedParts) return $mergedData;
        $includedParts = $assignedParts['included_part'];
        $excludedParts = $assignedParts['excluded_part'];

        foreach ($mergedData as &$item) {
            $temp = 0;
            $intersects = array_intersect_key($item, array_flip($transformedFields));
            $values = array_map(fn($element) => isset($element->original_value) ? $element->original_value : 'null', array_values($intersects));
            $elementAll = array_diff(array_values($values), $excludedParts);
            $total = count($elementAll);
            foreach ($values as $value) if (in_array($value, $includedParts)) $temp += 1;
            $progress = $total > 0 ? ($temp / $total) * 100 : 0;
            $progressStr = (string)number_format($progress, 2) . '%';
            if (isset($col['data_index'])) {
                $tooltip = "({$temp}/{$total})*100 = {$progressStr}";
                $this->setCellValue($item, $col['data_index'], $progress, $progressStr, $col['cell_class'], '', '', $tooltip);
            }
        }
        return $mergedData;
    }

    private function processTransformedFields($item, $transformedFields, $excludedParts, $includedParts, &$cols)
    {
        foreach ($transformedFields as $fieldIndex) {
            $isFullNa = false;
            if (isset($item[$fieldIndex])) {
                $fieldData = $item[$fieldIndex];
                $fieldObj = is_array($fieldData) ? (object)$fieldData : $fieldData;

                // Determine the value (prefer original_value, fallback to value)
                $value = isset($fieldObj->original_value) ? ($fieldObj->original_value ?: $fieldObj->value) : 'null';

                // Add to item_total if value is not in excludedParts
                if (!in_array($value, $excludedParts)) {
                    $cols[$fieldIndex]['item_total'][] = 1;
                } else {
                    $isFullNa = true;
                }

                // Add to item_finished if value is in includedParts
                if (in_array($value, $includedParts)) {
                    $cols[$fieldIndex]['item_finished'][] = 1;
                } else {
                    $isFullNa = true;
                }

                if ($isFullNa) {
                    $cols[$fieldIndex]['item_total'][] = 'NA';
                }
            }
        }
    }


    private function processAddedParts($item, $addedParts, &$otherCols)
    {
        foreach ($addedParts as $partKey) {
            if (isset($item[$partKey])) {
                if (!isset($otherCols[$partKey])) {
                    $otherCols[$partKey]['total'] = 0;
                    $otherCols[$partKey]['counted'] = 0;
                } 
                $otherCols[$partKey]['total'] += $item[$partKey];
                $otherCols[$partKey]['counted'] = $otherCols[$partKey]['counted'] ?? 0;
                if (isset($item[$partKey]->original_value)) $otherCols[$partKey]['total'] += $item[$partKey]->original_value;
                $otherCols[$partKey]['counted'] += 1;
            }
        }
    }
    // private function processAddedParts($item, $addedParts, &$otherCols)
    // {
    //     foreach ($addedParts as $partKey) {
    //         if (isset($item[$partKey])) {
    //             $otherCols[$partKey]['total'] = $otherCols[$partKey]['total'] ?? 0;
    //             $otherCols[$partKey]['counted'] = $otherCols[$partKey]['counted'] ?? 0;
    //             if (isset($item[$partKey]->original_value)) $otherCols[$partKey]['total'] += $item[$partKey]->original_value;
    //             $otherCols[$partKey]['counted'] += 1;
    //         }
    //     }
    // }
    private function processData($mergedData, $transformedFields, $excludedParts, $includedParts, $addedParts)
    {
        $cols = [];
        $otherCols = [];
        foreach ($mergedData as $item) {
            // Process transformed fields for this item
            $this->processTransformedFields($item, $transformedFields, $excludedParts, $includedParts, $cols);
            // Process added parts for this item
            $this->processAddedParts($item, $addedParts, $otherCols);
        }
        return ['transformed_col' => $cols, 'other_col' => $otherCols];
    }


    private function makeAggProgressCols($mergedData, $transformedFields, $configs)
    {
        $config = $configs['advanced_agg_col_config'] ?? [];
        if (!$config || empty($mergedData)) return $mergedData;
        $includedParts = $config['included_part'];
        $excludedParts = $config['excluded_part'];
        $addedParts = $config['other_col_to_calculate'];

        $type = $config['type'] ?? '';
        switch ($type) {
            case 'agg_progress':
                $result = $this->processData($mergedData, $transformedFields, $excludedParts, $includedParts, $addedParts);
                $transformedCols = $result['transformed_col'];
                $otherCols = $result['other_col'];

                $progresses = [];
                // Process transformed data columns
                $this->processOtherColumns($transformedCols, $progresses, $config);
                // Process non-transformed (added) columns
                $this->getAdvancedAggColConfig($otherCols, $progresses, $config);
                $firstData = reset($mergedData);
                $lackFields = array_fill_keys(array_diff(array_keys($firstData), $transformedFields), null);
                $mergedData = array_merge($mergedData, ['progress_row' => array_merge($lackFields, $progresses)]);
                // dd($mergedData['progress_row'], $progresses);
                return $mergedData;
            default:
                return $mergedData;
        }
    }


    private function getIntersectingValues($values, $transformedFields)
    {
        return array_filter($values, function ($key) use ($transformedFields) {
            return in_array($key, $transformedFields);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function createMatrix($configs, $data, $params, $customCols)
    {
        $column = $params['columns'];
        $row = $params['row'];
        $cellValue = $params['cell_value'];
        $valueToSet = $params['empty_value'] ?? null;
        $transformedFields = [];
        foreach ($data as &$rowData) {
            if (Report::checkValueOfField($rowData, $column)) {
                $col = $rowData->$column;
                if (!in_array($col, $transformedFields)) $transformedFields[] = $col;
                // To display row's value from 'grouping_to_matrix'
                $rowData = $this->makeValueForEachRow($configs, $rowData, $cellValue, $col, $valueToSet);
            }
        }

        $groupedByRow = Report::groupArrayByKey($data, $row);
        // dd($groupedByRow['TLCM00009']);
        $mergedData = array_map(fn($item) => array_merge(...$item), $groupedByRow);

        array_walk($mergedData, fn(&$value) => $this->fillMissingFields($value, $transformedFields, $valueToSet));

        if ($customCols) {
            foreach ($customCols as $col) {
                try {
                    $aggType = $col['agg_row'];
                    $dataIndex  = isset($col['data_index']) ? $col['data_index'] : '';
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
                                $this->setCellValue($values, $dataIndex, $total, $total, $col['cell_class']);
                            }
                            break;
                        case "agg_count_unique_values":
                            foreach ($mergedData as &$values) {
                                $intersect = $this->getIntersectingValues($values, $transformedFields);
                                $filteredArray = $this->filterValidValues($intersect);
                                $uniqueValues = array_unique(array_values($filteredArray));
                                $count = count($uniqueValues);
                                $this->setCellValue($values, $dataIndex, $count, $count, $col['cell_class']);
                            }
                            break;
                        case "agg_count_all":
                            foreach ($mergedData as &$values) {
                                $intersect = $this->getIntersectingValues($values, $transformedFields);
                                $filteredArray = $this->filterValidValues($intersect);
                                $num = count($filteredArray);
                                $this->setCellValue($values, $dataIndex, $num, $num, $col['cell_class']);
                            }
                            break;

                        case "agg_progress": // for rows
                            $mergedData = $this->makeAggProgressRows($mergedData, $transformedFields, $col);
                            break;

                        default:
                            break;
                    }
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    // Format the $col array into a more readable JSON string with line breaks after commas
                    $colStr = json_encode($col, JSON_PRETTY_PRINT);
                    // Build a more informative error message
                    $text = "An error occurred: " . $message;
                    $text .= "\n\nPlease verify your block configuration in 'Transformed Data Option (for Table, Chart)'.\n";
                    $text .= "The configuration provided is:\n";
                    $text .= $colStr;
                    throw new \ErrorException($text);
                }
            }
        }
        $mergedData = $this->makeAggProgressCols($mergedData, $transformedFields, $configs);
        // sort($transformedFields);
        return [array_values($mergedData), $transformedFields];
    }
}
