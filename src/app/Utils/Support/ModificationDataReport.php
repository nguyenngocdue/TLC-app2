<?php

namespace App\Utils\Support;


class ModificationDataReport
{
    public static function addFormulaForData($data, $arrayInfo)
    {
        foreach ($arrayInfo as $k => $items) {
            $href = $items['href'] ?? null;
            $color = $href ? 'text-blue-800' : null;
            $strFormulaByField = null;
            if (isset($items['formula'])) {
                $strFormulaByField = $items['formula'] ?? '';
                preg_match_all('/{{([^}]*)}}/', $strFormulaByField, $matches);
                foreach (last($matches) as $k2 => $value) {
                    if (isset($data[$value])) {
                        $val =  $data[$value];
                        if (is_object($val)) $val = $val->value;
                        $searchStr = head($matches)[$k2];
                        $strFormulaByField = str_replace($searchStr, $val, $strFormulaByField);
                    }
                }
            }
            $icon = isset($items['icon']) ? $items['icon'] :'';
            $data[$k] = (object)[
                'value' => $data[$k].$icon ?? '',
                'cell_href' => $href,
                'cell_title' => $strFormulaByField,
                'cell_class' => $color,
            ];
        }
        return $data;
    }
}
