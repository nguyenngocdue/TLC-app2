<?php

namespace App\Http\Controllers\Reports;

trait TraitCreateSQLReport2
{
    use TraitGenerateValuesFromParamsReport;

    public function replaceVariableStrs($sqlStr, $params)
    {
        //Match the variable name by {{ $variableName }}
        preg_match_all('/(?<!\\\)\{%\\s*([^}]*)\s*\%}/', $sqlStr, $matches);
        // dd($sqlStr, $matches);
        foreach (last($matches) as $key => $value) {
            $value = trim(str_replace('$', '', $value));
            if (isset($params[$value])) {
                $valOfParam = $params[$value];
                $tempStr = $params[$value];
                if (is_array($valOfParam)) {
                    $itemsIsNumeric = array_filter($valOfParam, fn($item) => is_numeric($item));
                    if (!empty($itemsIsNumeric)) $tempStr = implode(',', $valOfParam);
                    else {
                        $str = "";
                        foreach($valOfParam as $val)$str .= "'" . $val . "',";
                        $tempStr = trim($str, ",");
                    }
                }
                $searchStr = head($matches)[$key];
                $sqlStr = str_replace($searchStr, $tempStr, $sqlStr);
            } else {
                // Change the SQL String to the conrect syntax
                // $sqlStr = str_replace("'{{" . $value . "}}'", 'null', $sqlStr);
                $sqlStr = str_replace("{%" . $value . "%}", 'null', $sqlStr);
            }
        }
        $sqlStr = str_replace(["\{%", "\%}"], ["{%", "%}"], $sqlStr);
        // dd($sqlStr);
        return $sqlStr;
    }
    public function getSql($sqlString, $params)
    {
        $sqlStr = $this->replaceVariableStrs($sqlString, $params);
        // dd($sqlStr);
        return $sqlStr;
    }
}
