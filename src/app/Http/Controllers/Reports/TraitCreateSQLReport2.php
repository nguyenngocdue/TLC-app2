<?php

namespace App\Http\Controllers\Reports;

use App\View\Components\Reports2\TraitReportFormatString;
use Illuminate\Support\Facades\Log;

trait TraitCreateSQLReport2
{
    use TraitGenerateValuesFromParamsReport;
    use TraitReportFormatString;

    public function replaceVariableStrs($sqlStr, $params)
    {
        // Log::info($sqlStr, $params);
        $parsedVariables = $this->parseVariables($sqlStr);
        foreach (last($parsedVariables) as $key => $value) {
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
                        $tempStr = str_replace('\'', '',trim($str, ","));
                    }
                }
                $searchStr = head($parsedVariables)[$key];
                $sqlStr = str_replace($searchStr, $tempStr, $sqlStr);
            } else {
                // Change the SQL String to the correct syntax
                $sqlStr = str_replace("{%" . $value . "%}", 'null', $sqlStr);
                $sqlStr = str_replace("'null'", 'null', $sqlStr);
            }
        }
        $sqlStr = str_replace(["\{%", "\%}"], ["{%", "%}"], $sqlStr);
        $sqlStr = $this->evaluateAGG($sqlStr);
        return $sqlStr;
    }

    public function getSql($sqlString, $params)
    {
        $sqlStr = $this->replaceVariableStrs($sqlString, $params);
        // dd($sqlStr);
        return $sqlStr;
    }
}
