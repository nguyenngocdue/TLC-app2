<?php

namespace App\View\Components\Reports2;

trait TraitReportFormatString
{
    function parseVariables($sqlStr)
    {
        preg_match_all('/(?<!\\\)\{%\\s*([^}]*)\s*\%}/', $sqlStr, $parsedVariables);
        return $parsedVariables;
    }

    function formatReportHref($string, $dataLine)
    {
        $parsedVariables = $this->parseVariables($string);
        foreach (last($parsedVariables) as $key => $value) {
            $value = trim(str_replace('$', '', $value));
            if(!is_array($dataLine)) $dataLine = (array)$dataLine;
            if (isset($dataLine[$value])) {
                $valueParam =  $dataLine[$value];
                if (is_array($valueParam)) {
                    $itemsIsNumeric = array_filter($valueParam, fn($item) => is_numeric($item));
                    if (!empty($itemsIsNumeric)) $valueParam = implode(',', $valueParam);
                    else {
                        $str = "";
                        array_walk($valueParam, function ($item) use (&$str) {
                            $str .= "'" . $item . "',";
                        });
                        $valueParam = trim($str, ",");
                    }
                }
                $searchStr = head($parsedVariables)[$key];
                $string = str_replace($searchStr, $valueParam, $string);
            } else {
                return dd("Param '{$value}' not found in dataLine array", $string);
            }
        }
        // dd($string);
        return $string;
    }
}
