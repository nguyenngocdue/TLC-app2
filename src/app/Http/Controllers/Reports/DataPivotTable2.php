<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\View\Components\Renderer\Report\PivotReportColumn2;
use App\View\Components\Renderer\Report\PivotTableController;

class DataPivotTable2 extends Controller
{
   use TraitLibPivotTableDataFields2;
   use PivotReportColumn2;
   public function makeDataPivotTable($drawData, $modeType, $modeParams) {
      $fn = (new PivotTableController());
      $libs = $fn->getDataFields($drawData, $modeType);
      $dataOutput = $fn->makeDataRenderer($drawData, $libs, $modeParams);
      [$tableDataHeader, $tableColumns] = $fn->makeColumnsRenderer($drawData, $dataOutput, $libs, $modeType);
      $dataOutput = $fn->sortLinesData($dataOutput, $libs);
      
      return [$dataOutput, $tableColumns, $tableDataHeader];
   }
}
