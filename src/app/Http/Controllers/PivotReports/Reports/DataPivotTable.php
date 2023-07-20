<?php

namespace App\Http\Controllers\PivotReports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\View\Components\Renderer\Report\ColumnsPivotReport;
use App\View\Components\Renderer\Report\PivotTable;

class DataPivotTable extends Controller
{
   use TraitLibPivotTableDataFields;
   use ColumnsPivotReport;
   public function makeDataPivotTable($drawData, $modeType, $modeParams) {
      $fn = (new PivotTable);
      $allDataFields = $fn->getDataFields($drawData, $modeType);
      $dataOutput = $fn->makeDataRenderer($drawData, $allDataFields, $modeParams);
      // dd($dataOutput[0]);
      [$tableDataHeader, $tableColumns] = $fn->makeColumnsRenderer($drawData, $dataOutput, $allDataFields, $modeType);
      $dataOutput = $fn->sortLinesData($dataOutput, $allDataFields);
      
      return [ $dataOutput, $tableColumns, $tableDataHeader];
   }
}
