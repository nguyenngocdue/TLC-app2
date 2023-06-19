<?php

namespace App\Http\Controllers;



class WelcomeDueController_hr_timesheet_team_date extends WelcomeDueController
{
    protected $modeType = 'hr_timesheet_employee_date';
    protected function makeHeadColumn()
    {
        return [
            [
                "title" => "Workplace",
                "dataIndex" => "workplace_id",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "title" => "Category",
                "dataIndex" => "category_id",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "title" => "Type",
                "dataIndex" => "type_id",
                "align" => 'left',
                "width" => 80,
            ],
        ];
    }
    protected function makeColumnFields(){
        return [
            [
                'fieldIndex' => 'sub_project_id',
                'valueFieldIndex' => 'time_sheet_hours'
            ]
            ];
    }

}
