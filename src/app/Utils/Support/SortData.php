<?php

namespace App\Utils\Support;

class SortData
{
    public static function sortArrayByKeys($data, $fields)
    {
        usort($data, function ($a, $b) use ($fields) {
            foreach ($fields as $field) {
                $a = (array)$a;
                $b = (array)$b;
                if ($a[$field] == $b[$field]) {
                    continue;
                }
                return $a[$field] < $b[$field] ? -1 : 1;
            }
            return 0;
        });
        return $data;
    }
}
