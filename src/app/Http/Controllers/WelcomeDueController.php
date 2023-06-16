<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\Report;
use App\Utils\Support\ReportPivot;
use App\Utils\Support\ReportPivotDataFields;
use App\Utils\Support\Tree\BuildTree;
use AWS\CRT\HTTP\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }


    public function getDataSource()
    {
        $dataSource = [
            [
                "time_sheet_start_time" => "2022-10-18",
                "time_sheet_start_time_otr" => "2022-10-18",
                "time_sheet_start_time_wfh" => "2022-10-18",
                "time_sheet_hours" => 8,
                "time_sheet_hours_otr" => 2,
                "time_sheet_hours_wfh" => 3,
                "time_sheet_mins" => 600,
                "sub_project_id" => 82,
                "user_id" => 26,
                "discipline_id" => 45,
                "lod_id" => 228,
                "pj_task_id" => 57,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "sub_project_name" => "HLC N18",
                "time_sheet_lod_name" => "LOD400",
                "department_name" => "QA/QC",
                "staff_id" => "TLCM01071",
                "user_name" => "Binh Nguyen Nu Thien",
                "workplace_name" => "VN-TF3",
                "category_name" => "CMP",
                "type_name" => "Local",
                "discipline_name" => "Document Control (QA/QC)",
                "pj_task_name" => "QA/QC Documentation"
            ],
            [
                "time_sheet_start_time" => "2022-10-19",
                "time_sheet_start_time_otr" => "2022-10-19",
                "time_sheet_start_time_wfh" => "2022-10-19",
                "time_sheet_hours" => 8.0,
                "time_sheet_hours_otr" => 2,
                "time_sheet_hours_wfh" => 5,
                "time_sheet_mins" => 480,
                "sub_project_id" => 21,
                "user_id" => 26,
                "discipline_id" => 45,
                "lod_id" => 228,
                "pj_task_id" => 57,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "sub_project_name" => "HLC N17",
                "time_sheet_lod_name" => "LOD400",
                "department_name" => "QA/QC",
                "staff_id" => "TLCM01071",
                "user_name" => "Binh Nguyen Nu Thien",
                "workplace_name" => "VN-TF3",
                "category_name" => "CMP",
                "type_name" => "Local",
                "discipline_name" => "Document Control (QA/QC)",
                "pj_task_name" => "QA/QC Documentation"
            ],
            [
                "time_sheet_start_time" => "2022-10-20",
                "time_sheet_start_time_otr" => "2022-10-20",
                "time_sheet_start_time_wfh" => "2022-10-20",
                "time_sheet_hours" => 8.0,
                "time_sheet_hours_otr" => 2,
                "time_sheet_hours_wfh" => 7,
                "time_sheet_mins" => 480,
                "sub_project_id" => 21,
                "user_id" => 26,
                "discipline_id" => 45,
                "lod_id" => 228,
                "pj_task_id" => 57,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "sub_project_name" => "HLC N17",
                "time_sheet_lod_name" => "LOD400",
                "department_name" => "QA/QC",
                "staff_id" => "TLCM01071",
                "user_name" => "Binh Nguyen Nu Thien",
                "workplace_name" => "VN-TF3",
                "category_name" => "CMP",
                "type_name" => "Local",
                "discipline_name" => "Document Control (QA/QC)",
                "pj_task_name" => "QA/QC Documentation"
            ],
            [
                "time_sheet_start_time" => "2022-10-03",
                "time_sheet_start_time_otr" => "2022-10-03",
                "time_sheet_start_time_wfh" => "2022-10-03",
                "time_sheet_hours" => 8.0,
                "time_sheet_hours_otr" => 3,
                "time_sheet_hours_wfh" => 5,
                "time_sheet_mins" => 480,
                "sub_project_id" => 82,
                "user_id" => 26,
                "discipline_id" => 45,
                "lod_id" => 228,
                "pj_task_id" => 57,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "sub_project_name" => "HLC N18",
                "time_sheet_lod_name" => "LOD400",
                "department_name" => "QA/QC",
                "staff_id" => "TLCM01071",
                "user_name" => "Binh Nguyen Nu Thien",
                "workplace_name" => "VN-TF3",
                "category_name" => "CMP",
                "type_name" => "Local",
                "discipline_name" => "Document Control (QA/QC)",
                "pj_task_name" => "QA/QC Documentation"
            ],
            [
                "time_sheet_start_time" => "2022-11-28",
                "time_sheet_start_time_otr" => "2022-11-28",
                "time_sheet_start_time_wfh" => "2022-11-28",
                "time_sheet_hours" => 7.5,
                "time_sheet_hours_otr" => 7.5,
                "time_sheet_hours_wfh" => 7.5,
                "time_sheet_mins" => 450,
                "sub_project_id" => 82,
                "user_id" => 49,
                "discipline_id" => 16,
                "lod_id" => 228,
                "pj_task_id" => 207,
                "workplace_id" => 1,
                "type_id" => 2,
                "department_id" => 8,
                "category_id" => 24,
                "sub_project_name" => "HLC N18",
                "time_sheet_lod_name" => "LOD400",
                "department_name" => "QS",
                "staff_id" => "TLCM01060",
                "user_name" => "Anh Ha Hoang Tuan",
                "workplace_name" => "VN-HO",
                "category_name" => "QST",
                "type_name" => "Local",
                "discipline_name" => "QS",
                "pj_task_name" => "Detail BOM"
            ],
            [
                "time_sheet_start_time" => "2022-11-29",
                "time_sheet_start_time_otr" => "2022-11-29",
                "time_sheet_start_time_wfh" => "2022-11-29",
                "time_sheet_hours" => 8.5,
                "time_sheet_hours_otr" => 8.5,
                "time_sheet_hours_wfh" => 8.5,
                "time_sheet_mins" => 510,
                "sub_project_id" => 82,
                "user_id" => 49,
                "discipline_id" => 16,
                "lod_id" => 228,
                "pj_task_id" => 207,
                "workplace_id" => 1,
                "type_id" => 2,
                "department_id" => 8,
                "category_id" => 24,
                "sub_project_name" => "HLC N18",
                "time_sheet_lod_name" => "LOD400",
                "department_name" => "QS",
                "staff_id" => "TLCM01060",
                "user_name" => "Anh Ha Hoang Tuan",
                "workplace_name" => "VN-HO",
                "category_name" => "QST",
                "type_name" => "Local",
                "discipline_name" => "QS",
                "pj_task_name" => "Detail BOM"
            ],
            [
                "time_sheet_start_time" => "2022-11-30",
                "time_sheet_start_time_otr" => "2022-11-30",
                "time_sheet_start_time_wfh" => "2022-11-30",
                "time_sheet_hours" => 3,
                "time_sheet_hours_otr" => 4,
                "time_sheet_hours_wfh" => 3,
                "time_sheet_mins" => 180,
                "sub_project_id" => 82,
                "user_id" => 49,
                "discipline_id" => 16,
                "lod_id" => 228,
                "pj_task_id" => 207,
                "workplace_id" => 1,
                "type_id" => 2,
                "department_id" => 8,
                "category_id" => 24,
                "sub_project_name" => "HLC N18",
                "time_sheet_lod_name" => "LOD400",
                "department_name" => "QS",
                "staff_id" => "TLCM01060",
                "user_name" => "Anh Ha Hoang Tuan",
                "workplace_name" => "VN-HO",
                "category_name" => "QST",
                "type_name" => "Local",
                "discipline_name" => "QS",
                "pj_task_name" => "Detail BOM2"
            ],
            [
                "time_sheet_start_time" => "2022-11-14",
                "time_sheet_start_time_otr" => "2022-11-14",
                "time_sheet_start_time_wfh" => "2022-11-14",
                "time_sheet_hours" => 8,
                "time_sheet_hours_otr" => 7,
                "time_sheet_hours_wfh" => 3,
                "time_sheet_mins" => 480,
                "sub_project_id" => 82,
                "user_id" => 506,
                "discipline_id" => 15,
                "lod_id" => 228,
                "pj_task_id" => 61,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "sub_project_name" => "HLC N18",
                "time_sheet_lod_name" => "LOD400",
                "department_name" => "QA/QC",
                "staff_id" => "TLCM01219",
                "user_name" => "Diep Pham Dinh",
                "workplace_name" => "VN-TF3",
                "category_name" => "CMP",
                "type_name" => "Expat",
                "discipline_name" => "QA/QC",
                "pj_task_name" => "Work Inspection"
            ],
            [
                "time_sheet_start_time" => "2022-11-15",
                "time_sheet_start_time_otr" => "2022-11-15",
                "time_sheet_start_time_wfh" => "2022-11-15",
                "time_sheet_hours" => 8,
                "time_sheet_hours_otr" => 1,
                "time_sheet_hours_wfh" => 2,
                "time_sheet_mins" => 480,
                "sub_project_id" => 82,
                "user_id" => 506,
                "discipline_id" => 15,
                "lod_id" => 228,
                "pj_task_id" => 61,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "sub_project_name" => "HLC N18",
                "time_sheet_lod_name" => "LOD400",
                "department_name" => "QA/QC",
                "staff_id" => "TLCM01219",
                "user_name" => "Diep Pham Dinh",
                "workplace_name" => "VN-TF3",
                "category_name" => "CMP",
                "type_name" => "Expat",
                "discipline_name" => "QA/QC",
                "pj_task_name" => "Work Inspection"
            ],
            [
                "time_sheet_start_time" => "2022-11-16",
                "time_sheet_start_time_otr" => "2022-11-16",
                "time_sheet_start_time_wfh" => "2022-11-16",
                "time_sheet_hours" => 5,
                "time_sheet_hours_otr" => 1,
                "time_sheet_hours_wfh" => 2,
                "time_sheet_mins" => 480,
                "sub_project_id" => 82,
                "user_id" => 506,
                "discipline_id" => 15,
                "lod_id" => 228,
                "pj_task_id" => 61,
                "workplace_id" => 4,
                "type_id" => 2,
                "department_id" => 7,
                "category_id" => 3,
                "sub_project_name" => "HLC N18",
                "time_sheet_lod_name" => "LOD400",
                "department_name" => "QA/QC",
                "staff_id" => "TLCM01219",
                "user_name" => "Diep Pham Dinh",
                "workplace_name" => "VN-TF3",
                "category_name" => "CMP",
                "type_name" => "Expat",
                "discipline_name" => "QA/QC",
                "pj_task_name" => "Work Inspection2"
            ]
        ];
        return $dataSource;
    }


    function processData($lineData, $rowFields)
    {
        $dataOutput = [];
        foreach ($lineData as $line) {
            // Get the values of fields in $rowFields
            $params = [];
            foreach ($rowFields as $param) {
                if (isset($line[$param])) {
                    $params[$param] = $line[$param];
                } else {
                    $params[$param] = null;
                }
            }
            $nestedArray = &$dataOutput;
            foreach ($params as $paramValue) {
                // Create nested arrays in $dataOutput based on the values of fields in $rowFields
                if (!isset($nestedArray[$paramValue])) {
                    $nestedArray[$paramValue] = [];
                }
                $nestedArray = &$nestedArray[$paramValue];
            }
            if (!isset($nestedArray['output'])) $nestedArray['output'] = [];
            $nestedArray['output'][] = $line;
        }
        return $dataOutput;
    }

    function checkConditions($data, $conditions)
    {
        foreach ($conditions as $field => $values) {
            if (!isset($data[$field])) return false;
            if (!in_array($data[$field], $values)) return false;
        }
        return true;
    }
    function reduceData($linesData, $conditions)
    {
        $conditions = Report::dataWithoutNull($conditions);
        $result = array_filter($linesData, function ($data) use ($conditions) {
            return self::checkConditions($data, $conditions);
        });
        return $result;
    }


    private function transferData($dataSource, $columnFields)
    {
        return array_map(
            fn ($items) => array_map(fn ($array) =>
            ReportPivot::transferValueOfKeys($array, $columnFields), $items),
            $dataSource
        );
    }


    public function index(Request $request)
    {
        $lib = LibPivotTables::getFor("hr_timesheet_project_date");
        // dd($lib);
        $rowFields = $lib['row_fields'];
        $dataFields = $lib['data_fields'];
        $filters = $lib['filters'];
        $columnFields = $lib['column_fields'];
        // dd($filters, $linesData);
        $valueFilters = array_combine($filters, [[1, 4], [7, 8]]);

        $columnFields = [
            [
                'title' => 'time',
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

        // Step 1: reduce data from Filters columns
        $linesData = $this->getDataSource();
        $dataReduce = self::reduceData($linesData, $valueFilters);

        // Step 2: group data from Row Fields columns
        $dataProcess = self::processData($dataReduce, $rowFields);
        $dataProcess = array_values(array_map(fn ($item) => ReportPivot::getLastArray($item), $dataProcess));

        // Step 3: transfer data from Column Fields columns
        $transferData = $this->transferData($dataProcess, $columnFields);

        //Step 4: Calculate data from Data Fields columns
        $dataOutput = array_map(fn($items) => ReportPivotDataFields::executeFunctions($dataFields, $items), $transferData);
        $dataRender = ReportPivot::mergeChildrenValue($dataOutput);

        // dd($transferData, $dataRender);

  
        $column1 = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => 'left',
                "width" => 130,
            ],
            [
                'title' => "LOD",
                "dataIndex" => "time_sheet_lod_name",
                "align" => 'left',
                "width" => 130,
            ],
            [
                "title" => "Department",
                "dataIndex" => "department_name",
                "align" => 'left',
                "width" => 180,
            ],
            [
                "title" => "Staff ID",
                "dataIndex" => "staff_id",
                "align" => 'left',
                "width" => 120,
            ],
            [
                "title" => "User",
                "dataIndex" => "user_name",
                "align" => 'left',
                "width" => 200,
            ],
            [
                "title" => "Workplace",
                "dataIndex" => "workplace_name",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "title" => "Category",
                "dataIndex" => "category_name",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "title" => "Type",
                "dataIndex" => "type_name",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "title" => "Discipline",
                "dataIndex" => "discipline_name",
                "align" => 'left',
                "width" => 220,
            ],
            [
                "title" => "Task",
                "dataIndex" => "pj_task_name",
                "align" => 'left',
                "width" => 280,
            ],
        
        ];
        

        $col = [];
        foreach ($dataRender as $key => $value ) {
            // dd($value);
            $col = array_unique(array_merge($col, array_keys($value)));
        }
        $col = array_slice($col, 26, 1000);
        sort($col);

        $column2 = array_map(fn($item) => [
            'dataIndex' => $item,
            'align' => 'center',
            'width' => 50
        ], $col);

        $tableColumns = $column1 + $column2;

        return view("welcome-due", [
            'tableDataSource' => $dataRender,
            'tableColumns' => $tableColumns
        ]);
    }

}
