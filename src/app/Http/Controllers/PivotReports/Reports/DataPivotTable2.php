<?php

namespace App\Http\Controllers\PivotReports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitLibPivotTableDataFields2;
use App\View\Components\Renderer\Report\ColumnsPivotReport2;
use App\View\Components\Renderer\Report\PivotTable2;

class DataPivotTable2 extends Controller
{
   use TraitLibPivotTableDataFields2;
   use ColumnsPivotReport2;
   public function makeDataPivotTable($drawData, $modeType, $modeParams) {
      $fn = (new PivotTable2());
      $libs = $fn->getDataFields($drawData, $modeType);
      $dataOutput = $fn->makeDataRenderer($drawData, $libs, $modeParams);
      [$tableDataHeader, $tableColumns] = $fn->makeColumnsRenderer($drawData, $dataOutput, $libs, $modeType);
      $dataOutput = $fn->sortLinesData($dataOutput, $libs);
      
      return [$dataOutput, $tableColumns, $tableDataHeader];
   }
}
