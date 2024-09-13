<?php

namespace App\Utils\Support;

use App\View\Components\Reports2\TraitReportFormatString;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HrefReport
{

    use TraitReportFormatString;
    public function createDataHrefForRow($column, $dataLine)
    {
        $rowHrefFn = $column->row_href_fn;
        $string = $this->formatReportHref($rowHrefFn, $dataLine);

        [$variables, $fields]  = $allVariables;

        $result = array_combine(
            $variables,
            array_map(fn($field) => $dataLine->{$field} ?? null, $fields)
        );
        $href = str_replace($variables, array_values($result), $rowHrefFn);
        return $href;
    
    }
}
