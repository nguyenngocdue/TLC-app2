<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Helpers\Helper;
use App\Http\Controllers\Reports\Report_ParentController;
use App\Models\Workplace;
use App\Utils\Support\Report;

class Hr_overtime_request_line extends Report_ParentController
{
    public function getSqlStr($urlParams)
    {
        // dd($urlParams);
        $sql = " SELECT 
            wp.name AS workplace, rgt_ot.*, MONTH(rgt_ot.overtime_date) AS overtime_month
            FROM (SELECT 
                otline.id AS id_request_line
                ,otline.user_id AS user_id
                ,us.workplace AS workplace_id
                ,otline.employeeid AS employeeid
                ,us.full_name AS member_name
                ,otline.ot_date AS overtime_date
                ,otline.total_time AS total_overtime_hours
                ,(60-otline.total_time ) AS permit_overtime_hours
                    FROM users us, hr_overtime_request_lines otline
                    WHERE 1 = 1
                    AND otline.user_id = us.id";
        if (isset($urlParams['overtime_month'])) $sql .= "\n AND MONTH(otline.ot_date) = '{{overtime_month}}'";
        $sql .= ") AS rgt_ot \n";

        $sql .= "JOIN workplaces wp ON wp.id = rgt_ot.workplace_id";
        if (isset($urlParams['workplace_id'])) $sql .= "\n AND wp.id = '{{workplace_id}}'";
        $sql .= "\n ORDER BY overtime_date DESC";
        return $sql;
    }
    public function getTableColumns($dataSource = [])
    {
        // dump($dataSource);
        return [
            [
                "title" => 'ID',
                "dataIndex" => "user_id",
                "renderer" => "id",
                "type" => 'users',
                "align" => 'center'
            ],
            [
                "dataIndex" => "employeeid",
                "align" => 'left'
            ],
            [
                "dataIndex" => "workplace",
                "align" => 'left'
            ],
            [
                "dataIndex" => "member_name",
                "align" => "Left",
            ],
            [
                "dataIndex" => "total_overtime_hours",
                "align" => "right",
            ],
            [
                "dataIndex" => "permit_overtime_hours",
                "align" => "right",
            ],
        ];
    }


    public function getDataForModeControl($dataSource = [])
    {
        // dd($dataSource);
        $workplaces = ['workplace_id' => Workplace::get()->pluck('name', 'id')->toArray()];
        $months = ['overtime_month' => array_unique(array_column($dataSource, 'overtime_date', 'overtime_month'))];
        return array_merge($workplaces, $months);
    }
}
