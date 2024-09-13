<?php

namespace App\Utils\Support;

use App\View\Components\Reports2\TraitReportFormatString;

class RegexReport
{
    use TraitReportFormatString;
    public static function getAllVariables($string)
    {
        $parsedVariables = self::parseVariables($string);
        return $parsedVariables;
    }
}
