<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Http\Controllers\UpdateUserSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Hr_overtime_request_020 extends Report_ParentController
{
    use TraitReport;
    protected $groupBy = 'ot_date';
    protected $mode = '020';


    public function getSqlStr($modeParams)
    {
        $sql = "SELECT
        sp.name sub_project_name
        ,otr.id hr_overtime_request_id
        ,otline.sub_project_id sub_project_id
        ,otline.id request_id,
        otline.user_id user_id,
        us.first_name first_name,
        us.last_name last_name,
        otline.employeeid employee_id
        ,otline.ot_date ot_date
        ,otline.ot_date AS ot_date
        ,otline.from_time from_time
        ,otline.break_time break_time
        ,otline.to_time to_time
        ,otline.total_time total_time
        ,otline.month_allowed_hours month_allowed_hours
        ,otline.month_remaining_hours month_remaining_hours
        ,otline.year_allowed_hours year_allowed_hours
        ,otline.year_remaining_hours year_remaining_hours
        FROM hr_overtime_request_lines otline, sub_projects sp, users us, hr_overtime_requests otr
        WHERE 1 = 1";

        if (isset($modeParams['user_id'])) $sql .= "\n AND otline.user_id = '{{user_id}}'";
        if (isset($modeParams['months'])) $sql .= "\n AND SUBSTR(otline.ot_date,1,7) = '{{months}}'";
        $sql .= "\n AND otline.sub_project_id = sp.id
                    AND us.id = otline.user_id
                    AND otr.id = otline.hr_overtime_request_id
                    ORDER BY first_name, last_name, employee_id, ot_date DESC";
        return $sql;
    }

    public function getTableColumns($dataSource, $modeParams)
    {
        $personDataCol = [
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
                "dataIndex" => "request_id",
                "align" => "center",
                "renderer" => "qr_code",
                "type" => "hr_overtime_request_lines",
            ]
        ];
        $editDataCols = [[
            "title" => "Date",
            "dataIndex" => "ot_date",
            "align" => "center"
        ], [
            "title" => "Break Time (Mins)",
            "dataIndex" => "break_time",
            "align" => "center"
        ]];
        // dd($dataSource);
        $sqlDataCol = $this->createTableColumns($dataSource, 'first_name', 'year_remaining_hours', $editDataCols);
        $totalDataCol = array_merge($personDataCol, $sqlDataCol);
        return  $totalDataCol;
    }
    protected function getParamColumns()
    {
        return [
            [
                'dataIndex' => 'months',
                'allowClear' => true,
            ],
            [
                'title' => 'User',
                'dataIndex' => 'user_id',
                'allowClear' => true,
            ]
        ];
    }

    protected function getDataModes()
    {
        return ['mode_option' => ['010' => 'Overtime Summary ', '020' => 'User Overtime']];
    }


    private function getAllMonths()
    {
        $sql = "SELECT DISTINCT(SUBSTR(otline.ot_date, 1, 7)) AS year_months
                    FROM hr_overtime_request_lines otline
                    ORDER BY year_months DESC";
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }

    private function getAllOtUser()
    {
        $sql = "SELECT DISTINCT(us.id) AS user_id, us.name
                FROM hr_overtime_request_lines otline, users us
                WHERE us.id = otline.user_id ORDER BY user_id";
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }

    public function getDataForModeControl($dataSource)
    {
        $sqlMonths = $this->getAllMonths();
        $mon = array_column($sqlMonths, 'year_months');
        $months = ['months' => array_combine($mon, $mon)];

        $sqlUsers = $this->getAllOtUser();
        $us = array_column($sqlUsers, 'name',  'user_id');
        $users = ['user_id' => $us];

        return array_merge($months, $users);
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {


        return $modeParams;
    }

    protected function enrichDataSource($dataSource, $modeParams)
    {
        foreach ($dataSource as $key => $value) {
            $htmlEmployeeId = "<span title='User ID: $value->user_id'>$value->employee_id</span>";
            $dataSource[$key]->employee_id = $htmlEmployeeId;
        }
        return collect($dataSource);
    }

    protected function forwardToMode($request, $typeReport, $entity)
    {
        $input = $request->input();

        if (isset($input['months']) || isset($input['user_id'])) {
            // Log::info("020");
            $params = [
                '_entity' => $entity,
                'action' => 'updateReport' . $typeReport,
                'type_report' => $typeReport,
                'mode_option' => $this->mode
            ] + $input;
            $request->replace($params);
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
