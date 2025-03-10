<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\Reports\Hr_timesheet_line_100;
use Illuminate\Http\Request;


class DemoPivotTableDataSource extends Controller
{

    protected $modeType = '';
    public function getType()
    {
        return "dashboard";
    }

    private function getDataSource1()
    {
        $primaryData = (new Hr_timesheet_line_100())->getDataSource([]);
        $primaryData = array_map(fn($item) =>(array)$item, $primaryData->toArray());
        $primaryData = [
            [
                "ts_date" => "2022-10-18",
                "time_sheet_hours" => 8,
                "time_sheet_hours_otr" => 2,
                "time_sheet_hours_wfh" => 3,
                "time_sheet_mins" => 480,
                "sub_project_id" => 82,
                "user_id" => 26,
                "lod_id" => 228,
                "pj_task_id" => 57,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "discipline_id" => 82,
                // "position_1" => null,
                // "position_2" => 51,
                // "position_3" => 20,
                'assignee_1_id' =>20,
                'assignee_id' =>1,
                "staff_id" => "TLCM01219",
            ],
            [
                "ts_date" => "2022-10-19",
                "time_sheet_hours" => 8.0,
                "time_sheet_hours_otr" => 2,
                "time_sheet_hours_wfh" => 5,
                "time_sheet_mins" => 480,
                "sub_project_id" => 21,
                "user_id" => 26,
                "lod_id" => 228,
                "pj_task_id" => 57,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "discipline_id" => 82,
                // "position_1" => null,
                // "position_2" => 51,
                // "position_3" => 20,
                'assignee_1_id' =>20,
                'assignee_id' =>1,
                "staff_id" => "TLCM01071",
            ],
            [
                "ts_date" => "2022-10-20",
                "time_sheet_hours" => 8.0,
                "time_sheet_hours_otr" => 2,
                "time_sheet_hours_wfh" => 7,
                "time_sheet_mins" => 480,
                "sub_project_id" => 82,
                "user_id" => 26,
                "lod_id" => 228,
                "pj_task_id" => 57,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "discipline_id" => 82,
                // "position_1" => null,
                // "position_2" => 51,
                // "position_3" => 20,
                'assignee_1_id' =>31,
                'assignee_id' =>5,
                "staff_id" => "TLCM01071",
            ],
            [
                "ts_date" => "2022-10-03",
                "time_sheet_hours" => 8.0,
                "time_sheet_hours_otr" => 3,
                "time_sheet_hours_wfh" => 5,
                "time_sheet_mins" => 480,
                "sub_project_id" => 21,
                "user_id" => 26,
                "lod_id" => 228,
                "pj_task_id" => 57,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "discipline_id" => 82,
                // "position_1" => null,
                // "position_2" => 51,
                // "position_3" => 20,
                'assignee_1_id' =>41,
                'assignee_id' =>4,
                "staff_id" => "TLCM01071",
            ],
            [
                "ts_date" => "2022-11-28",
                "time_sheet_hours" => 7.5,
                "time_sheet_hours_otr" => 6,
                "time_sheet_hours_wfh" => 7.5,
                "time_sheet_mins" => 450,
                "sub_project_id" => 82,
                "user_id" => 49,
                "lod_id" => 228,
                "pj_task_id" => 207,
                "workplace_id" => 1,
                "type_id" => 2,
                "department_id" => 8,
                "category_id" => 24,
                "discipline_id" => 122,
                // "position_1" => null,
                // "position_2" => 51,
                // "position_3" => null,
                'assignee_1_id' =>51,
                'assignee_id' =>5,
                "staff_id" => "TLCM01060",
            ],
            [
                "ts_date" => "2022-11-28",
                "time_sheet_hours" => 7.5,
                "time_sheet_hours_otr" => 6,
                "time_sheet_hours_wfh" => 7.5,
                "time_sheet_mins" => 450,
                "sub_project_id" => 82,
                "user_id" => 49,
                "lod_id" => 228,
                "pj_task_id" => 207,
                "workplace_id" => 1,
                "type_id" => 1,
                "department_id" => 8,
                "category_id" => 24,
                "discipline_id" => 122,
                // "position_1" => null,
                // "position_2" => 51,
                // "position_3" => null,
                'assignee_1_id' =>20,
                'assignee_id' =>01,
                "staff_id" => "TLCM01067",
            ],
            [
                "ts_date" => "2022-11-29",
                "time_sheet_hours" => 7.5,
                "time_sheet_hours_otr" => 6,
                "time_sheet_hours_wfh" => 7.5,
                "time_sheet_mins" => 450,
                "sub_project_id" => 82,
                "user_id" => 49,
                "lod_id" => 228,
                "pj_task_id" => 207,
                "workplace_id" => 1,
                "type_id" => 2,
                "department_id" => 8,
                "category_id" => 24,
                "discipline_id" => 122,
                // "position_1" => null,
                // "position_2" => 51,
                // "position_3" => null,
                'assignee_1_id' =>71,
                'assignee_id' =>7,
                "staff_id" => "TLCM01068",
            ],
            [
                "ts_date" => "2022-11-29",
                "time_sheet_hours" => 8.5,
                "time_sheet_hours_otr" => 1.5,
                "time_sheet_hours_wfh" => 5,
                "time_sheet_mins" => 510,
                "sub_project_id" => 82,
                "user_id" => 49,
                "lod_id" => 228,
                "pj_task_id" => 207,
                "workplace_id" => 1,
                "type_id" => 2,
                "department_id" => 8,
                "category_id" => 24,
                "discipline_id" => 122,
                // "position_1" => null,
                // "position_2" => 51,
                // "position_3" => null,
                'assignee_1_id' =>51,
                'assignee_id' =>1,
                "staff_id" => "TLCM01060",
            ],
            [
                "ts_date" => "2022-11-30",
                "time_sheet_hours" => 3,
                "time_sheet_hours_otr" => 4,
                "time_sheet_hours_wfh" => 3,
                "time_sheet_mins" => 180,
                "sub_project_id" => 82,
                "user_id" => 49,
                "lod_id" => 228,
                "pj_task_id" => 207,
                "workplace_id" => 1,
                "type_id" => 2,
                "department_id" => 8,
                "category_id" => 24,
                "discipline_id" => 122,
                // "position_1" => null,
                // "position_2" => 51,
                // "position_3" => null,
                'assignee_1_id' =>91,
                'assignee_id' =>9,
                "staff_id" => "TLCM01060",
            ],
            [
                "ts_date" => "2022-11-14",
                "time_sheet_hours" => 8,
                "time_sheet_hours_otr" => 7,
                "time_sheet_hours_wfh" => 3,
                "time_sheet_mins" => 480,
                "sub_project_id" => 82,
                "user_id" => 506,
                "lod_id" => 228,
                "pj_task_id" => 61,
                "workplace_id" => 4,
                "type_id" => 1,
                "department_id" => 7,
                "category_id" => 3,
                "discipline_id" => 23,
                // "position_1" => 57,
                // "position_2" => 50,
                // "position_3" => null,
                'assignee_1_id' =>1,
                'assignee_id' =>1,
                "staff_id" => "TLCM01219",
            ],
            [
                "ts_date" => "2022-11-14",
                "time_sheet_hours" => 8,
                "time_sheet_hours_otr" => 7,
                "time_sheet_hours_wfh" => 3,
                "time_sheet_mins" => 480,
                "sub_project_id" => 82,
                "user_id" => 506,
                "lod_id" => 228,
                "pj_task_id" => 61,
                "workplace_id" => 7,
                "type_id" => 1,
                "department_id" => 7,
                "category_id" => 3,
                "discipline_id" => 23,
                // "position_1" => 57,
                // "position_2" => 50,
                // "position_3" => null,
                'assignee_1_id' =>20,
                'assignee_id' =>1,
                "staff_id" => "TLCM01219",
            ],
            [
                "ts_date" => "2022-11-15",
                "time_sheet_hours" => 8,
                "time_sheet_hours_otr" => 1,
                "time_sheet_hours_wfh" => 2,
                "time_sheet_mins" => 480,
                "sub_project_id" => 82,
                "user_id" => 506,
                "lod_id" => 228,
                "pj_task_id" => 61,
                "workplace_id" => 4,
                "type_id" => 1,
                "department_id" => 7,
                "category_id" => 3,
                "discipline_id" => 23,
                // "position_1" => 57,
                // "position_2" => 50,
                // "position_3" => null,
                'assignee_1_id' =>20,
                'assignee_id' =>1,
                "staff_id" => "TLCM01219",
            ],
            [
                "ts_date" => "2022-11-16",
                "time_sheet_hours" => 5,
                "time_sheet_hours_otr" => 1,
                "time_sheet_hours_wfh" => 2,
                "time_sheet_mins" => 300,
                "sub_project_id" => 82,
                "user_id" => 506,
                "lod_id" => 228,
                "pj_task_id" => 61,
                "workplace_id" => 4,
                "type_id" => 1,
                "department_id" => 7,
                "category_id" => 3,
                "discipline_id" => 23,
                // "position_1" => 57,
                // "position_2" => 50,
                // "position_3" => null,
                'assignee_1_id' =>20,
                'assignee_id' =>1,
                "staff_id" => "TLCM01219",
            ]
        ];
        // dump($primaryData);

        $primaryData = [
            ["category" => "phone", "product" => "iPad", "date" => "2024-01-01", "price" => 1000, "discount" => 10,"amount" => 990, 'sale_price' => 900],
            ["category" => "phone", "product" => "iPad", "date" => "2024-01-06", "price" => 1000, "discount" => 20,"amount" => 990, 'sale_price' => 800],
            ["category" => "phone", "product" => "iPad", "date" => "2024-01-06", "price" => 1000, "discount" => 0,"amount" => 1000, 'sale_price' => 1000],
            ["category" => "phone", "product" => "iPhone", "date" => "2024-01-01", "price" => 1200, "discount" => 12,"amount" => 1188, 'sale_price' => 1056],
            ["category" => "phone", "product" => "iPhone", "date" => "2024-01-05", "price" => 1200, "discount" => 0,"amount" => 1200, 'sale_price' => 1200],
            ["category" => "computer", "product" => "MacMini", "date" => "2024-01-01", "price" => 1000, "discount" => 10,"amount" => 990, 'sale_price' => 900],
            ["category" => "computer", "product" => "MacBook", "date" => "2024-01-01", "price" => 2000, "discount" => 20,"amount" => 1980, 'sale_price' => 1600],
            ["category" => "computer", "product" => "iMac", "date" => "2024-01-01", "price" => 2500, "discount" => 25,"amount" => 2475, 'sale_price' => 1875],
            ["category" => "computer", "product" => "iPad", "date" => "2024-01-06", "price" => 1000, "discount" => 50,"amount" => 800, 'sale_price' => 1000],
            ["category" => "computer", "product" => "MacMini", "date" => "2024-01-02", "price" => 1000, "discount" => 0,"amount" => 1000, 'sale_price' => 1000],
            ["category" => "computer", "product" => "MacBook", "date" => "2024-01-03", "price" => 2000, "discount" => 0,"amount" => 2000, 'sale_price' => 2000],
            ["category" => "computer", "product" => "iMac", "date" => "2024-01-04", "price" => 2500, "discount" => 0,"amount" => 2500, 'sale_price' => 2500],

        ];
        return $primaryData;
    }

    public function index(Request $request)
    {
        return view("welcome-due", [
            'key' => $this->modeType,
            'dataSource' => $this->getDataSource1()
        ]);
    }
}
