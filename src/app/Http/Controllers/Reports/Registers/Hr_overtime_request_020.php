<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use Illuminate\Support\Facades\DB;


class Hr_overtime_request_020 extends Report_ParentController
{
    use TraitReport;
    protected $groupBy = 'year_months';
    protected $mode = '020';


    public function getSqlStr($modeParams)
    {
        $sql = "SELECT
        sp.name sub_project_name
        ,otline.sub_project_id sub_project_id
        ,otline.id request_id,
        otline.user_id user_id,
        us.first_name first_name,
        us.last_name last_name,
        otline.employeeid employee_id
        ,otline.ot_date ot_date
        ,SUBSTR(otline.ot_date,1,7) AS year_months
        ,otline.from_time from_time
        ,otline.break_time break_time
        ,otline.to_time to_time
        ,otline.remaining_hours remaining_hours
        FROM hr_overtime_request_lines otline, sub_projects sp, users us
        WHERE 1 = 1";

        if (isset($modeParams['user_id'])) $sql .= "\n AND otline.user_id = '{{user_id}}'";
        if (isset($modeParams['months'])) $sql .= "\n AND SUBSTR(otline.ot_date,1,7) = '{{months}}'";
        $sql .= "\n AND otline.sub_project_id = sp.id
                    AND us.id = otline.user_id
                    ORDER BY first_name, last_name, employee_id, year_months DESC";
        return $sql;
    }


    public function getTableColumns($dataSource)
    {

        return   [
            [
                "dataIndex" => "sub_project_name",
                "align" => "center"
            ],
            [
                "dataIndex" => "request_id",
                "align" => "center"
            ],
            [
                "dataIndex" => "first_name",
                "align" => "center"
            ],
            [
                "dataIndex" => "last_name",
                "align" => "center"
            ],
            [
                "dataIndex" => "employee_id",
                "align" => "center"
            ],
            [
                "dataIndex" => "year_months",
                "align" => "center"
            ],
            [
                "dataIndex" => "from_time",
                "align" => "center"
            ],
            [
                "dataIndex" => "break_time",
                "align" => "center"
            ],
            [
                "dataIndex" => "to_time",
                "align" => "center"
            ],
            [
                "dataIndex" => "total_time",
                "align" => "center"
            ],
            [
                "dataIndex" => "remaining_hours",
                "align" => "center"
            ],

        ];
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



    protected function setDefaultValueModeParams($modeParams, $request)
    {
        // dd($request);
        return $modeParams;
    }




    protected function enrichDataSource($dataSource, $modeParams)
    {
        return collect($dataSource);
    }
}
