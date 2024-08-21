<?php

namespace App\Http\Controllers\Reports;

trait TraitCreateSQLReport2
{
    use TraitGenerateValuesFromParamsReport;

    public function replaceVariableStrs($sqlStr, $params)
    {
        //Match the variable name by {{ $variableName }}
        //TOFIX: lay toi thieu
        preg_match_all('/(?<!\\\)\{\{\s*([^}]*)\s*\}\}/', $sqlStr, $matches);
        foreach (last($matches) as $key => $value) {
            $value = trim(str_replace('$', '', $value));
            if (isset($params[$value])) {
                $valueParamArr =  $params[$value];
                // dd($matches, $params, $valueParamArr);
                $valueParamStr = "";
                if (is_array($valueParamArr)) {
                    $itemsIsNumeric = array_filter($valueParamArr, fn($item) => is_numeric($item));
                    if (!empty($itemsIsNumeric)) $valueParamStr = implode(',', $valueParamArr);
                    else {
                        $str = "";
                        //TOFIX: use array map
                        array_walk($valueParamArr, function ($item) use (&$str) {
                            $str .= "'" . $item . "',";
                        });
                        $valueParamStr = trim($str, ",");
                    }
                }
                $searchStr = head($matches)[$key];
                $sqlStr = str_replace($searchStr, $valueParamStr, $sqlStr);
            } else {
                // Change the SQL String to the conrect syntax
                $sqlStr = str_replace("'{{" . $value . "}}'", 'null', $sqlStr);
            }
        }
        $sqlStr = str_replace(["\{{", "\}}"], ["{{", "}}"], $sqlStr);
        return $sqlStr;
    }
    public function getSql($sqlString, $params)
    {
        $sqlStr = $this->replaceVariableStrs($sqlString, $params);
        // dd($sqlStr);
        return $sqlStr;
    }
}
