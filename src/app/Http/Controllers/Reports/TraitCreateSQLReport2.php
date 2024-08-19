<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;
use App\Utils\Support\Report;

trait TraitCreateSQLReport2
{
    use TraitGenerateValuesFromParamsReport;

    public function preg_match_all($sqlStr, $params)
    {
        preg_match_all('/(?<!\\\)\{\{\s*([^}]*)\s*\}\}/', $sqlStr, $matches);
        foreach (last($matches) as $key => $value) {
            $value = trim(str_replace('$', '', $value));
            if (isset($params[$value])) {
                $valueParam =  $params[$value];
                // dump($matches);
                // dump($matches, $params, $valueParam);
                if (is_array($valueParam)) {
                    $itemsIsNumeric = array_filter($valueParam, fn ($item) => is_numeric($item));
                    if (!empty($itemsIsNumeric)) $valueParam = implode(',', $valueParam);
                    else {
                        $str = "";
                        array_walk($valueParam, function ($item) use (&$str) {
                            $str .= "'" . $item . "',";
                        });
                        $valueParam = trim($str, ",");
                    }
                }
                $searchStr = head($matches)[$key];
                $sqlStr = str_replace($searchStr, $valueParam, $sqlStr);
            } else {
                return dd("Param '{$value}' not found in params array",$params, $sqlStr);
            }
        }
        if (Report::checkParam($params, 'picker_date')) {
            $dates = DateReport::separateStrPickerDate($params['picker_date']);
            $sqlStr = str_replace('{{end_date}}', $dates['end'], $sqlStr);
            $sqlStr = str_replace('{{start_date}}', $dates['start'], $sqlStr);
        }
        $sqlStr = str_replace(["\{{", "\}}"], ["{{", "}}"], $sqlStr);
        return $sqlStr;
    }
    public function getSql($sqlString, $params)
    {
        if (isset($params['picker_date']) && $x = $params['picker_date']) {
            $params['picker_date'] = DateReport::formatDateString($x);
        }
        $sqlStr = $this->preg_match_all($sqlString, $params);
        return $sqlStr;
    }
}
