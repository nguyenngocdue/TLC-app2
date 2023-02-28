<?php

namespace App\Http\Controllers\Reports;

trait TraitReport
{
    function isNullUrlParams($urlParams)
    {
        return count(array_filter($urlParams, fn ($value) => !is_null($value))) <= 0;
    }
}
