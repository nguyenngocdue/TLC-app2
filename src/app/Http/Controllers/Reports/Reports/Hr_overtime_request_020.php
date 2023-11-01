<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\Http\Controllers\UpdateUserSettings;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentUser;
use DateTime;
use Illuminate\Support\Str;

class Hr_overtime_request_020 extends Report_ParentReport2Controller
{
    use TraitDynamicColumnsTableReport;
    use TraitModifyDataToExcelReport;
    use TraitUserCompanyTree;

    protected $groupBy = 'ot_date';
    protected $mode = '020';
    protected $maxH = 50;
    protected $typeView = 'report-pivot';

    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);

        // dd($params);
        $pickerDate =  isset($params['picker_date']) ? $params['picker_date'] : '';
        // dd($fromDate);
        $sql = "SELECT 	uswp.name workplace_name,tb1.*
        FROM 
        (SELECT
        sp.name sub_project_name
        ,otr.id hr_overtime_request_id
        ,otline.sub_project_id sub_project_id
        ,wp.name AS ot_workplace_name
        ,us.workplace us_workplace_id
        ,otline.id request_id,
        otline.user_id user_id,
        us.name0 name_render,
        otline.employeeid employee_id
        ,otline.ot_date ot_date
        ,wm.name work_mode_name
        ,otline.break_time break_time
        ,SUBSTR(otline.from_time, 1,5) from_time
        ,SUBSTR(otline.to_time, 1,5) to_time
        ,otline.total_time total_time
        ,otline.month_allowed_hours month_allowed_hours
        ,otline.month_remaining_hours month_remaining_hours
        ,otline.year_allowed_hours year_allowed_hours
        ,uscate.name AS user_category_name
        ,if(day(otline.ot_date) BETWEEN 1 AND 25, substr(SUBSTR(otline.ot_date,1,20), 1,7), substr(DATE_ADD(SUBSTR(otline.ot_date,1,20), INTERVAL 1 MONTH),1,7)) AS years_month 
        ,otline.year_remaining_hours year_remaining_hours

        FROM hr_overtime_request_lines otline, sub_projects sp, users us, hr_overtime_requests otr, work_modes wm, workplaces wp, user_categories uscate
        WHERE 1 = 1
                AND otline.deleted_at IS  NULL
                AND sp.deleted_at IS NULL
                AND us.deleted_at IS NULL
                AND otr.deleted_at IS NULL
                AND wm.deleted_at IS NULL
        ";

if (isset($params['user_id'])) $sql .= "\n AND otline.user_id = '{{user_id}}'";
        if ($pickerDate) {
            $fromDate = DateTime::createFromFormat('d-m-Y', str_replace('/', '-', substr($pickerDate, 0, 10)))->format('Y-m-d');
            $toDate = DateTime::createFromFormat('d-m-Y', str_replace('/', '-', substr($pickerDate, 13, strlen($pickerDate))))->format('Y-m-d');
            $sql .= "\n AND otline.ot_date >= '$fromDate'
            AND otline.ot_date <= '$toDate'";
        }
        if (!CurrentUser::isAdmin()) {
            $treeData = $this->getDataByCompanyTree();
            $userIds = array_column($treeData, 'id');
            if (!count($userIds)) return "";
            $strUserIds = '(' . implode(',', $userIds) . ')';
            $sql .= "\n AND otline.user_id IN $strUserIds";
        }
        $sql .= "\n AND otline.sub_project_id = sp.id
                    AND otr.id = otline.hr_overtime_request_id
                    AND otr.status LIKE 'approved'
                    AND us.id = otline.user_id
                    AND otline.work_mode_id = wm.id 
                    AND otr.workplace_id = wp.id
                    AND us.category = uscate.id";
        if (isset($params['ot_workplace_id'])) $sql .= "\n AND otr.workplace_id = '{{ot_workplace_id}}'";
        $sql .= " \n  ) AS tb1
                    LEFT JOIN workplaces uswp ON uswp.id = tb1.us_workplace_id ";
        if (isset($params['month'])) $sql .= "\n WHERE tb1.years_month  = {{month}}";
        $sql .= "\n ORDER BY name_render, employee_id, ot_date DESC";
        return $sql;
    }

    public function getTableColumns($dataSource, $params)
    {
        // dump($dataSource);
        $totalDataCol = [
            [
                "dataIndex" => "sub_project_name",
                "align" => "center",
                "width" => 150,
            ],
            [
                "title" => "OT Workplace",
                "dataIndex" => "ot_workplace_name",
                "align" => 'left',
                "width" => 150,
            ],
            [
                "title" => "Work Mode",
                "dataIndex" => "work_mode_name",
                "align" => 'left',
                "width" => 200,
            ],
            [
                "title" => "OT ID",
                "dataIndex" => "hr_overtime_request_id",
                "align" => "center",
                "width" => 150,
                "renderer" => "qr_code",
                "type" => "hr_overtime_requests",
            ],
            [
                "title" => "Employee ID",
                "dataIndex" => "employee_id",
                "align" => 'left',
                "width" => 150,
            ],
            [
                "title" => "Full Name",
                "dataIndex" => "name_render",
                "align" => 'left',
                "width" => 350,
            ],
            [
                "title" => "User Workplace",
                "dataIndex" => "workplace_name",
                "align" => 'left',
                "width" => 150,
            ],
            [
                "title" => "Team",
                "dataIndex" => "user_category_name",
                "align" => 'left',
                "width" => 150,
            ],
            [
                "title" => "OT Date",
                "dataIndex" => "ot_date",
                "align" => "center",
                "width" => 300,
            ],
            [
                "title" => "From Time",
                "dataIndex" => "from_time",
                "align" => "center",
                "width" => 150,
            ],
            [
                "title" => "To Time",
                "dataIndex" => "to_time",
                "align" => "center",
                "width" => 150,
            ],
            [
                "title" => "Break Time (Mins)",
                "dataIndex" => "break_time",
                "align" => "right",
                "width" => 150,
            ],
            [
                "title" => "Total Time",
                "dataIndex" => "total_time",
                "align" => "right",
                "width" => 150,
            ],
            [
                "dataIndex" => "month_allowed_hours",
                "align" => "right",
                "width" => 150,
            ],
            [
                "dataIndex" => "month_remaining_hours",
                "align" => "right",
                "width" => 150,
            ],
            [
                "dataIndex" => "year_allowed_hours",
                "align" => "right",
                "width" => 150,
            ],
            [
                "dataIndex" => "year_remaining_hours",
                "align" => "right",
                "width" => 150,
            ],

        ];
        return  $totalDataCol;
    }
    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Date',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
                'allowClear' => true,
                'validation' => 'date_format:d/m/Y',
            ],
            [
                'title' => 'User',
                'dataIndex' => 'user_id',
                'allowClear' => true,
            ],
        ];
    }


    protected function getDefaultValueParams($params, $request)
    {
        return $params;
    }

    protected function enrichDataSource($dataSource, $params)
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

    protected function forwardToMode($request, $params)
    {
        $input = $request->input();
        if (isset($input['month']) || isset($input['user_id'])) {
            $typeReport = Str::ucfirst(CurrentPathInfo::getTypeReport2($request));
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
            $routeName = explode('/', $request->getPathInfo())[2];
            return redirect(route($routeName));
        }
    }
}
