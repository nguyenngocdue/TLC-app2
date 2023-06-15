<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\Report;
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
                "time_sheet_hours" => 8.0,
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
                "time_sheet_start_time" => "2022-10-19",
                "time_sheet_hours" => 8.0,
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
                "time_sheet_mins" => 480,
                "time_sheet_hours" => 8.0,
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
                "time_sheet_mins" => 480,
                "time_sheet_hours" => 8.0,
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
                "time_sheet_mins" => 450,
                "time_sheet_hours" => 7.5,
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
                "type_name" => "Local",
                "discipline_name" => "QS",
                "pj_task_name" => "Detail BOM"
            ],
            [
                "time_sheet_start_time" => "2022-11-29",
                "time_sheet_mins" => 510,
                "time_sheet_hours" => 8.5,
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
                "time_sheet_hours" => 3,
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
                "time_sheet_mins" => 480,
                "time_sheet_hours" => 8,
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
                "time_sheet_mins" => 480,
                "time_sheet_hours" => 8,
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
                "time_sheet_mins" => 480,
                "time_sheet_hours" => 8,
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


    function processData($lineData, $rowFields, $dataFields) {
        $dataOutput = [];
        foreach ($lineData as $line) {
            // get value of lines
            $params = [];
            foreach ($rowFields as $param) {
                if (isset($line[$param])) {
                    $params[$param] = $line[$param];
                } else {
                    $params[$param] = null;
                }
            }
            $valueDataFields = [];
            foreach ($dataFields as $field) {
                $valueDataFields[$field] = isset($line[$field]) ? $line[$field] : 0;
                unset($params[$field]);
            }
            $nestedArray = &$dataOutput;
            foreach ($params as $paramValue) {
                if (!isset($nestedArray[$paramValue])) {
                    $nestedArray[$paramValue] = [];
                }
                $nestedArray = &$nestedArray[$paramValue];
            }
            foreach ($valueDataFields as $field => $valueField) {
                if (!isset($nestedArray[$field])) {
                    $nestedArray[$field] = 0;
                }
                $nestedArray[$field] += $valueField;
            }
        }
        // dd($dataOutput);
        return $dataOutput;
    }

    function checkConditions($data, $conditions) {
        foreach ($conditions as $field => $values) {
            if (!isset($data[$field])) return false;
            if (!in_array($data[$field], $values)) return false;
        }
        return true;
    }
    function reduceData($linesData,$conditions){
        $conditions = Report::dataWithoutNull($conditions);
        $result = array_filter($linesData, function($data) use ($conditions) {
            return self::checkConditions($data, $conditions);
        });
        return $result;
    }

    public function index(Request $request)
    {
        $lib = LibPivotTables::getFor("hr_timesheet_project_date");
        $linesData = $this->getDataSource();
        $rowFields = $lib['row_fields'];
        $dataFields = $lib['data_fields'];
        $filters = $lib['filters'];
        $valueFilters = array_combine($filters,[[4],[8,7]]);

        $poorData = self::reduceData($linesData,$valueFilters);
        $dataOutput = self::processData($poorData, $rowFields, $dataFields);

        dd($dataOutput, $linesData);




        // dd($strIndex);
        return view("welcome-due", [
            // ''
        ]);
    }
}
