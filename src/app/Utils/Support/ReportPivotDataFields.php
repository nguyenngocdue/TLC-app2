<?php

namespace App\Utils\Support;

use Illuminate\Support\Str;
use DateTime;

class ReportPivotDataFields
{
    private static function addResultIntoData($data, $result, $fieldName, $method)
    {
        return array_map(function ($item) use ($result, $fieldName, $method) {
            return array_merge($item, [$method . '_' . $fieldName => $result]);
        }, $data);
    }

    public static function executeFunctions($fieldNames, $data)
    {
        foreach ($fieldNames as $fieldName) {
            $parts = explode('-', $fieldName, 2);
            $method = $parts[0];
            $field = $parts[1] ?? '';
            foreach ($data as &$items) {
                $result = null;
                switch ($method) {
                    case 'sum':
                        $result = array_sum(array_column($items, $field));
                        break;
                    case 'concat':
                        $result = implode('', array_column($items, $field));
                        break;
                    default:
                        break;
                }
                $items = self::addResultIntoData($items, $result, $field, $method);
            }

        }
        // dd($data);
        return $data;
    }
}
