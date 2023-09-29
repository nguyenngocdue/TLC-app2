<?php

namespace App\Utils\Support;


class ModificationDataReport
{
    public static function addFormulaForData($data, $arrayInfo)
    {
        foreach ($arrayInfo as $k => $items) {
            $href = $items['href'] ?? null;
            $color = $href ? 'text-blue-800' : null;
            $strCellTitle = null;
            if (isset($items['cell_title'])) {
                $strCellTitle = $items['cell_title'] ?? '';
                preg_match_all('/{{([^}]*)}}/', $strCellTitle, $matches);
                foreach (last($matches) as $k2 => $value) {
                    if (isset($data[$value])) {
                        $val =  $data[$value];
                        if (is_object($val)) $val = $val->value;
                        $searchStr = head($matches)[$k2];
                        $strCellTitle = str_replace($searchStr, $val, $strCellTitle);
                    }
                }
            }
            $icon = isset($items['icon']) ? $items['icon'] :'';
            $data[$k] = (object)[
                'value' => ($icon ?'<div class="flex justify-end items-center ">'.$data[$k].$icon.'</div>' :  $data[$k]) ?? '',
                'cell_href' => $href,
                'cell_title' => $strCellTitle,
                'cell_class' => $color,
            ];
        }
        return $data;
    }
}
