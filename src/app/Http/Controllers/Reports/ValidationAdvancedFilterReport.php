<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Workflow\LibPivotTables2;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\DateReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ValidationAdvancedFilterReport
{
    private function checkValidationAdvancedFilter(Request $request)
    {
        $columns = $this->getParamColumns([], '');
        if (empty(last($columns))){
            $modeKey = CurrentPathInfo::getModeKey($request);
            $columns = array_map(fn($item) => (array)$item, LibPivotTables2::getFor($modeKey)['filters']);
        };
        $fields = array_column($columns,'validation', 'dataIndex');
        $values = $request->all();
        if(isset($values['picker_date'])) {
            $pickerDate = $values['picker_date'];
            [$start, $end] =  DateReport::explodePickerDate($pickerDate);
            $fields['start_date'] = "date_format:d/m/Y";
            $fields['end_date'] = "date_format:d/m/Y";
            $values['start_date'] = $start;
            $values['end_date'] = $end;
        }
        $validator = Validator::make($values, $fields);
        if ($validator->fails()) {
            // dd($validator->messages());
            return $validator;
        }
    }
}