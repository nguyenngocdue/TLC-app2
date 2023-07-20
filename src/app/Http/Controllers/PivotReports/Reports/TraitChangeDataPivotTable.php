<?php

namespace App\Http\Controllers\PivotReports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\View\Components\Renderer\Report\ColumnsPivotReport;
use App\View\Components\Renderer\Report\PivotTable;

trait TraitChangeDataPivotTable
{
   use TraitLibPivotTableDataFields;
   public function changeValueData($data)
   {
      $lib = LibPivotTables::getFor($this->modeType);
      $lib = self::removeEmptyElements($lib);
      $bindingRowFields = $this->separateFields3($lib['row_fields']);
      $results = [];
      foreach ($data as $values) {
          foreach ($values as $key => $value) {
              foreach (array_keys($bindingRowFields) as $bindingKey) {
                  if (str_contains($key, $bindingKey) && strlen($key) > strlen($bindingKey)) {
                      $values[$key] = (object) [
                        'value' => $value,
                        'cell_title'=> 'ID: '.(string)$values[$bindingKey],
                     ];
                  }
              }
          }
          $results[] = $values;
      }
      return $results;
   }
}
