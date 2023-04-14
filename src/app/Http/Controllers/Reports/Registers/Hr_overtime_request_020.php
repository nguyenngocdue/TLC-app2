<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentRegisterController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Reports\TraitSQLDataSourceParamReport;
use App\Http\Controllers\UpdateUserSettings;
use App\Utils\Support\CurrentPathInfo;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Hr_overtime_request_020 extends Report_ParentRegisterController
{
    use TraitDynamicColumnsTableReport;
    use TraitSQLDataSourceParamReport;
    use TraitModifyDataToExcelReport;

    protected $groupBy = 'ot_date';
    protected $mode = '020';

    public function getSqlStr($modeParams)
    {
        $pickerDate =  isset($modeParams['picker_date']) ? $modeParams['picker_date'] : '';
        // dd($fromDate);
        $sql = "SELECT 	tb1.*
        FROM 
        (SELECT
        sp.name sub_project_name
        ,otr.id hr_overtime_request_id
        ,otline.sub_project_id sub_project_id
        ,otline.id request_id,
        otline.user_id user_id,
        us.name name_render,
        otline.employeeid employee_id
        ,otline.ot_date ot_date
        ,otline.break_time break_time
        ,SUBSTR(otline.from_time, 1,5) from_time
        ,SUBSTR(otline.to_time, 1,5) to_time
        ,otline.total_time total_time
        ,otline.month_allowed_hours month_allowed_hours
        ,otline.month_remaining_hours month_remaining_hours
        ,otline.year_allowed_hours year_allowed_hours
        ,if(day(otline.ot_date) BETWEEN 1 AND 25, substr(SUBSTR(otline.ot_date,1,20), 1,7), substr(DATE_ADD(SUBSTR(otline.ot_date,1,20), INTERVAL 1 MONTH),1,7)) AS years_month 
        ,otline.year_remaining_hours year_remaining_hours

        FROM hr_overtime_request_lines otline, sub_projects sp, users us, hr_overtime_requests otr
        WHERE 1 = 1";

        if (isset($modeParams['user_id'])) $sql .= "\n AND otline.user_id = '{{user_id}}'";
        if ($pickerDate) {
            $fromDate = DateTime::createFromFormat('d-m-Y', str_replace('/', '-', substr($pickerDate, 0, 10)))->format('Y-m-d');
            $toDate = DateTime::createFromFormat('d-m-Y', str_replace('/', '-', substr($pickerDate, 13, strlen($pickerDate))))->format('Y-m-d');
            $sql .= "\n AND otline.ot_date >= '$fromDate'
            AND otline.ot_date <= '$toDate'";
        }
        $sql .= "\n AND otline.sub_project_id = sp.id
                    AND otr.id = otline.hr_overtime_request_id
                    AND  otr.status LIKE 'approved'
                    AND us.id = otline.user_id
                        ) AS tb1";
        if (isset($modeParams['month'])) $sql .= "\n WHERE tb1.years_month  = '{{month}}'";
        $sql .= "\n ORDER BY name_render, employee_id, ot_date DESC";
        return $sql;
    }

    public function getTableColumns($dataSource, $modeParams)
    {
        // dump($dataSource);
        $totalDataCol = [
            [
                "dataIndex" => "sub_project_name",
                "align" => "center"
            ],
            [
                "title" => "HR Overtime Request",
                "dataIndex" => "hr_overtime_request_id",
                "align" => "center",
                "renderer" => "qr_code",
                "type" => "hr_overtime_requests",
            ],
            [
                "title" => "Employee ID",
                "dataIndex" => "employee_id",
                "align" => 'left'
            ],
            [
                "title" => "Full Name",
                "dataIndex" => "name_render",
                "align" => 'left'
            ],
            [
                "title" => "OT Date",
                "dataIndex" => "ot_date",
                "align" => "center"
            ],
            [
                "title" => "From Time",
                "dataIndex" => "from_time",
                "align" => "center"
            ],
            [
                "title" => "To Time",
                "dataIndex" => "to_time",
                "align" => "center"
            ],
            [
                "title" => "Break Time (Mins)",
                "dataIndex" => "break_time",
                "align" => "right"
            ],
            [
                "title" => "Total Time",
                "dataIndex" => "total_time",
                "align" => "right",
            ],
            [
                "dataIndex" => "month_allowed_hours",
                "align" => "right",
            ],
            [
                "dataIndex" => "month_remaining_hours",
                "align" => "right",
            ],
            [
                "dataIndex" => "year_allowed_hours",
                "align" => "right",
            ],
            [
                "dataIndex" => "year_remaining_hours",
                "align" => "right",
            ],

        ];
        return  $totalDataCol;
    }
    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Date',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
                'allowClear' => true,
            ],
            [
                'title' => 'User',
                'dataIndex' => 'user_id',
                'allowClear' => true,
            ],
        ];
    }


    protected function getDefaultValueModeParams($modeParams, $request)
    {
        return $modeParams;
    }

    protected function enrichDataSource($dataSource, $modeParams)
    {
        foreach ($dataSource as $key => $value) {
            $dataSource[$key]->employee_id = (object)[
                'value' => $value->employee_id,
                'cell_title' => 'User ID: ' . $value->user_id,
            ];
        }
        // dd($dataSource);
        return collect($dataSource);
    }

    protected function forwardToMode($request, $modeParams)
    {
        $input = $request->input();
        if (isset($input['month']) || isset($input['user_id'])) {
            $typeReport = Str::ucfirst(CurrentPathInfo::getTypeReport($request));
            $entityReport = CurrentPathInfo::getEntityReport($request);
            // dd($typeReport, $entityReport);
            $params = [
                '_entity' => $entityReport,
                'action' => 'updateReport' . $typeReport,
                'type_report' => $typeReport,
                'mode_option' => $this->mode
            ] + $input;
            $request->replace($params);
            (new UpdateUserSettings())($request);
            // dd($input);
            return redirect($request->getPathInfo());
        }

        // update : page, params
        $isFormType = isset($input['form_type']);
        if ($isFormType && $input['form_type'] === 'updateParamsReport' || $isFormType && $input['form_type'] === 'updatePerPageReport') {
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }
        if (isset($input['mode_option'])) {
            $mode = $input['mode_option'];
            $routeName = explode('/', $request->getPathInfo())[2];
            return redirect(route($routeName . '_' . $mode));
        }
    }
}
