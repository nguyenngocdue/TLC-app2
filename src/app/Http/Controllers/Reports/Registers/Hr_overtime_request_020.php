<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;

class Hr_overtime_request_020 extends Report_ParentController
{
    use TraitReport;
    protected $groupBy = 'first_name';
    public function getSqlStr($modeParams)
    {
        dd(12312313);
        $sql = "SELECT
        sp.name sub_project_name
        ,otline.sub_project_id sub_project_id
        ,otline.id request_id,
        otline.user_id user_id,
        otline.employeeid employee_id
        ,otline.ot_date ot_date
        ,SUBSTR(otline.ot_date,1,7) AS year_months
        ,otline.from_time from_line
        ,otline.break_time break_time
        ,otline.to_time to_time
        ,otline.total_time total_time
        ,otline.rt_remaining_hours rt_remaining_hours
        FROM hr_overtime_request_lines otline, sub_projects sp
        WHERE 1 = 1 
            AND	otline.user_id = 46
            AND otline.sub_project_id = sp.id";
        return $sql;
    }



    public function getTableColumns($dataSource)
    {
        dump($dataSource);
        return [
            [
                'title' => 'OT Workplace',
                "dataIndex" => "sub_project_name",
                "align" => 'left'
            ]
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
        dd($dataSource);
        return $dataSource;
    }
}
