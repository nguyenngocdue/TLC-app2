<?php
namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ValidationAdvancedFilterReport
{
    private function checkValidationAdvancedFilter(Request $request)
    {
        $fields = array_column($this->getParamColumns([], ''),'validation', 'dataIndex');
        if(empty($fields)) return [];
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
            return $validator;
        }
    }
}