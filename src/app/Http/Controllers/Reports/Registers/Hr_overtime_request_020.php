<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;

class Hr_overtime_request_020 extends Report_ParentController
{
    use TraitReport;
    protected $groupBy = 'first_name';
    protected $mode = '020';

    public function getSqlStr($modeParams)
    {
        // dump($modeParams);
        // $userId =  $modeParams['user_id'];
        $sql = "SELECT
        sp.name sub_project_name
        ,otline.sub_project_id sub_project_id
        ,otline.id request_id,
        otline.user_id user_id,
        us.full_name user_full_name,
        otline.employeeid employee_id
        ,otline.ot_date ot_date
        ,SUBSTR(otline.ot_date,1,7) AS year_months
        ,otline.from_time from_time
        ,otline.break_time break_time
        ,otline.to_time to_time
        ,otline.total_time total_time
        ,otline.rt_remaining_hours rt_remaining_hours
        FROM hr_overtime_request_lines otline, sub_projects sp, users us
        WHERE 1 = 1
        AND	otline.user_id =";
        // $sql .= "$userId";
        $sql .= "46";
        $sql .= "\n AND otline.sub_project_id = sp.id
                    AND us.id = otline.user_id";
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
                "title" => "Full Name",
                "dataIndex" => "user_full_name",
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
                "title" => "RT Remaining Hours",
                "dataIndex" => "rt_remaining_hours",
                "align" => "center"
            ],

        ];
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Mode Options',
                'dataIndex' => 'mode_control',
            ]
        ];
    }

    public function getDataForModeControl($dataSource)
    {
    }


    protected function enrichDataSource($dataSource, $modeParams)
    {
        return collect($dataSource);
    }
}
