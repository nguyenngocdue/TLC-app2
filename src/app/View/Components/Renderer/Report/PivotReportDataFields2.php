<?php

namespace App\View\Components\Renderer\Report;

class PivotReportDataFields2
{


    private static function execute($aggregations, $data)
    {
        if (!count($aggregations)) return [];
        $newData = [];
        foreach ($aggregations as $field => $info) {
            $result = null;
            $operator = $info->aggregation;
            foreach ($data as $key => $items) {
                switch ($operator) {
                    case 'sum':
                        $result = array_sum(array_column($items, $field));
                        break;
                    case 'concat':
                        $source = array_unique(array_column($items, $field));
                        $result = implode(', ', $source);
                        break;
                    default:
                        $result = "Unknown operator '" . $operator . "'";
                        break;
                }
                $newData[$key][$operator . '_' . $field] = $result;
            }
        }
        // dd($data[0], $newData);
        return $newData;
    }

    private static function calculateSubArraysTotal($array)
    {
        $total = 0;

        foreach ($array as $item) {
            if (is_array($item)) {
                foreach ($item as $subItem) {
                    if (isset($subItem[key($subItem)])) {
                        $total += $subItem[key($subItem)];
                    } else if (is_array($subItem)) {
                        $total += self::calculateSubArraysTotal([$subItem]);
                    }
                }
            }
        }
        return $total;
    }

    private static function updateSumAmount($array, $newValue)
    {
        $updatedArray = [];
        foreach ($array as $item) {
            if (is_array($item)) {
                foreach ($item as $key => $subItem) {
                    if (isset($subItem[key($subItem)])) {
                        $updatedArray[$key] = [key($subItem) => $newValue];
                        break;
                    } else if (is_array($subItem)) {
                        $updatedArray = self::updateSumAmount($subItem, $newValue);
                    }
                }
            }
        }
        // dd($updatedArray);
        return $updatedArray;
    }

    public static function executeOperations($dataAggregations, $data, $rowFields)
    {
        if(empty($dataAggregations)) return $data;
        $arrayValue = array_map(fn ($items) => PivotReportDataFields2::execute($dataAggregations, $items), $data);

        if (!$rowFields) {
            $totalNumber = self::calculateSubArraysTotal($arrayValue);
            $updateArrayValue = self::updateSumAmount($arrayValue, $totalNumber);
            // dd($updateArrayValue);
            return $updateArrayValue;
        }
        $totalNumber = self::calculateSubArraysTotal($arrayValue);
        // dd($data, $totalNumber, $arrayValue);
        return $arrayValue;
    }
}
