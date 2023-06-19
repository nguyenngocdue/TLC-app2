<?php

namespace App\Http\Controllers;


class WelcomeDueController_hr_timesheet_employee_date extends WelcomeDueController
{
    protected $modeType = 'hr_timesheet_employee_date';
    protected function makeHeadColumn()
    {
        return  [
            [
                "title" => "Staff ID",
                "dataIndex" => "staff_id",
                "align" => 'left',
                "width" => 120,
            ],
            [
                "title" => "User",
                "dataIndex" => "user_id",
                "align" => 'left',
                "width" => 200,
            ],
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
            [
                "title" => "Department",
                "dataIndex" => "department_id",
                "align" => 'left',
                "width" => 280,
            ],

        ];
    }
    protected function makeColumnFields()
    {
        return [
            [
                'fieldIndex' => 'time_sheet_start_time',
                'valueFieldIndex' => 'time_sheet_hours'
            ],
            [
                'title' => 'otr',
                'fieldIndex' => 'time_sheet_start_time_otr',
                'valueFieldIndex' => 'time_sheet_hours_otr'
            ],
            [
                'title' => 'wfh',
                'fieldIndex' => 'time_sheet_start_time_wfh',
                'valueFieldIndex' => 'time_sheet_hours_wfh'
            ],
        ];
    }
}
