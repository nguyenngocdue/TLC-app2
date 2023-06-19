<?php

namespace App\Utils\Support;

use Illuminate\Support\Str;
use DateTime;

class ReportPivotDataFields
{
    private static function addResultIntoData($data, $result, $fieldName, $method)
    {
        return array_map(function ($item) use ($result, $fieldName, $method) {
            return array_merge( [$method . '_' . $fieldName => $result], $item);
        }, $data);
    }

    public static function executeFunctions($dataAggregations, $data)
    {
        foreach ($dataAggregations as $field => $fn) {
            foreach ($data as &$items) {
                $result = null;
                switch ($fn) {
                    case 'sum':
                        $result = array_sum(array_column($items, $field));
                        break;
                    case 'concat':
                        $result = implode('', array_column($items, $field));
                        break;
                    default:
                        break;
                }
                $items = self::addResultIntoData($items, $result, $field, $fn);
            }

        }
        return $data;
    }
}
