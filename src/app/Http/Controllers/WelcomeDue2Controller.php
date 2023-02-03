<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Reports\Report_ParentController2;


class WelcomeDue2Controller extends Report_ParentController2
{
    protected $viewName = "welcome-due-2";
    public function getSqlStr()
    {
        return " SELECT
        po.id
        ,s.id AS sheet_id
        ,r.id AS run_id
        ,l.id AS line_id
        ,l.name AS line_name
        ,l.description AS line_description
        
        ,cv.id AS control_value
        ,cv.name AS control_value_name
        ,cv.qaqc_insp_control_group_id control_group_id
    
        ,ct.id AS control_type_id
        ,ct.name AS control_type_name
        ,g.id AS group_id
        ,g.description AS group_description
        ,ts.id AS tmpl_sheet_id
        ,ts.description AS tmpl_sheet_description
        
        ,divide_control.c1
        ,divide_control.c2
        ,divide_control.c3
        ,divide_control.c4
        
        
    FROM qaqc_insp_chklst_runs r
        JOIN qaqc_insp_chklst_shts s ON r.qaqc_insp_chklst_sht_id = s.id
        JOIN prod_orders po ON po.id = 230
        JOIN qaqc_insp_chklst_lines l ON l.qaqc_insp_chklst_run_id = r.id
        JOIN control_types ct ON ct.id = l.control_type_id
        LEFT JOIN qaqc_insp_control_values cv ON l.qaqc_insp_control_value_id = cv.id
        JOIN qaqc_insp_groups g ON g.id = l.qaqc_insp_group_id
        JOIN qaqc_insp_tmpl_shts ts ON ts.id = s.qaqc_insp_tmpl_sht_id
       LEFT JOIN (
                SELECT id as control_group_id
                , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 1)), '|', 1)) AS c1
                , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 2)), '|', 1)) AS c2
                , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 3)), '|', 1)) AS c3
                , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 4)), '|', 1)) AS c4
                FROM qaqc_insp_control_groups AS cg
                        )  AS divide_control ON l.qaqc_insp_control_group_id = divide_control.control_group_id
       
    WHERE r.qaqc_insp_chklst_sht_id = 1
    ORDER BY control_value
        
        ";
    }
    public function getTableColumns()
    {
        return [
            [
                "title" => 'ID.',
                "dataIndex" => "line_id",
                "renderer" => "id",
                "align" => "center",
            ],
            [
                "title" => 'Description',
                "dataIndex" => "line_description",
            ],



            [
                "dataIndex" => "c1",
                "align" => "right",
            ],
            [
                "dataIndex" => "c2",
                "align" => "right",
            ],
            [
                "dataIndex" => "c3",
                "align" => "right",
            ],
            [
                "dataIndex" => "c4",
                "align" => "right",
            ],



            [
                "dataIndex" => "group_description",
                "align" => "right",
            ],
            [
                "dataIndex" => "line_id",
                "align" => "right",
            ],
            [
                "dataIndex" => "control_value_name",
                "align" => "right",
            ]
        ];
    }
}
