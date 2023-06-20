<?php

namespace App\Utils\Support;

use Illuminate\Support\Str;
use DateTime;

class ReportPivotDataFields
{

    public static function executeOperations($dataAggregations, $data)
    {
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
}
