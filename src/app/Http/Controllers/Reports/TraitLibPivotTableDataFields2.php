<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Workflow\LibPivotTables2;
trait TraitLibPivotTableDataFields2
{
    public function getDataFields($dataSource, $modeType)
    {
        $libs = LibPivotTables2::getFor($modeType);
        return $libs;
    }
}
