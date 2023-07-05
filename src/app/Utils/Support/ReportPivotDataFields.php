<?php

namespace App\Utils\Support;

use Illuminate\Support\Str;
use DateTime;

class ReportPivotDataFields
{


    private static function execute($dataAggregations, $data)
    {
        if (!count($dataAggregations)) return [];
        $newData = [];
        foreach ($dataAggregations as $field => $operator) {
            $result = null;
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
                        $updatedArray[$key]= [key($subItem) => $newValue];
                        break;
                    } else if (is_array($subItem)) {
                        $updatedArray = self::updateSumAmount($subItem, $newValue);
                    }
                }
            }
        }
        return $updatedArray;
    }

    public static function executeOperations($dataAggregations,$transferredData,$data, $rowFields,$columnFields)
    {
        // dd($data, $dataAggregations);
        $arrayValue = array_map(fn ($items) => ReportPivotDataFields::execute($dataAggregations, $items), $data);

        if (!$rowFields) {
            $totalNumber = self::calculateSubArraysTotal($arrayValue);
            $updateArrayValue = self::updateSumAmount($arrayValue, $totalNumber);
            return $updateArrayValue;
        }
        return $arrayValue;
    }
}
