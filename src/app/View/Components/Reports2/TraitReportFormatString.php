<?php

namespace App\View\Components\Reports2;

trait TraitReportFormatString
{
    function parseVariables($sqlStr)
    {
        preg_match_all('/(?<!\\\)\{%\\s*([^}]*)\s*\%}/', $sqlStr, $parsedVariables);
        return $parsedVariables;
    }
}
