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
                // Change the SQL String to the conrect syntax
                $variables = reset($matches);
                foreach ($variables as $key => $value) {
                    $sqlStr = str_replace("'".$value."'", 'null', $sqlStr);
                }
            }
        }
        $sqlStr = str_replace(["\{{", "\}}"], ["{{", "}}"], $sqlStr);
        return $sqlStr;
    }
    public function getSql($sqlString, $params)
    {
        $sqlStr = $this->preg_match_all($sqlString, $params);
        return $sqlStr;
    }
}
