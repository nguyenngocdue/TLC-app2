<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\Report;
use App\Utils\Support\ReportPivot;
use App\Utils\Support\ReportPivotDataFields;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;


class WelcomeDueController extends Controller
{
    protected $modeType = '';
    public function getType()
    {
        return "dashboard";
    }


    private function getDataSource()
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
                "time_sheet_start_time" => "2022-10-03",
                "time_sheet_start_time_otr" => "2022-10-03",
                "time_sheet_start_time_wfh" => "2022-10-03",
                "time_sheet_hours" => 8.0,
                "time_sheet_hours_otr" => 3,
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
                "time_sheet_start_time" => "2022-11-28",
                "time_sheet_start_time_otr" => "2022-11-28",
                "time_sheet_start_time_wfh" => "2022-11-28",
                "time_sheet_hours" => 7.5,
                "time_sheet_hours_otr" => 6,
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
                "time_sheet_hours_otr" => 1.5,
                "time_sheet_hours_wfh" => 5,
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


    private function processData($lineData, $rowFields)
    {
        $dataOutput = [];
        foreach ($lineData as $line) {
            // Get the values of fields in $rowFields
            $params = [];
            foreach ($rowFields as $param) {
                if (isset($line[$param])) {
                    $params[$param] = $line[$param];
                } else $params[$param] = null;
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

    private function checkConditions($data, $conditions)
    {
        foreach ($conditions as $field => $values) {
            if (!isset($data[$field])) return false;
            if (!in_array($data[$field], $values)) return false;
        }
        return true;
    }
    
    private function reduceData($linesData, $conditions)
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

    private function getDatesBetween($startDate, $endDate)
    {
        $dateList = array();
        $start = DateTime::createFromFormat('d/m/Y', $startDate);
        $end = DateTime::createFromFormat('d/m/Y', $endDate);
        $end = $end->modify('+1 day');
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);
        foreach ($dateRange as $date) {
            $dateList[] = $date->format('d/m/Y');
        }
        return $dateList;
    }

    private function getStringDates($modeParams)
    {
        $pickerDate = $modeParams['picker_date'] ?? '13/11/2022-01/12/2022';
        [$startDate, $endDate] = Report::explodePickerDate($pickerDate);
        $dates = self::getDatesBetween($startDate, $endDate);
        $strDates = array_map(fn ($item) => str_replace('/', '_', $item), $dates);
        return $strDates;
    }

    protected function makeHeadColumn(){
        return [[]];
    }
    protected function makeColumnFields(){
        return [[]];
    }


    private function makeColumns( $dataOutput, $columnFields)
    {
        // Create Column Render
        $column1 = $this->makeHeadColumn();
        $col = [];
        foreach ($dataOutput as $value) $col = array_unique(array_merge($col, array_keys($value)));
        $fieldDates = Report::retrieveDataByIndex($col, 'pj_task_name', false, 'value');
        $titles = array_column($columnFields, 'title');
        $data =  ReportPivot::sortItems($fieldDates, $titles);

        $column2 = array_map(fn ($item) => [
            'title' => str_replace('_', '/', $item),
            'dataIndex' => $item,
            'align' => 'center',
            'width' => 40
        ], $data);

        [$rowFields,$filters, $dataAggregations] =  $this->getDataFields();
        $column3 = [];
        foreach ($dataAggregations as $filed => $fn) {
            $column3[] = [
            'dataIndex' => $fn.'_'.$filed,
            'align' => 'right',
            'width' => 40
            ];
        };
        $tableColumns = array_merge($column1, $column2, $column3);
        return $tableColumns;
    }


    private function getDataFields(){
        $lib = LibPivotTables::getFor($this->modeType);
        $dataFields = $lib['data_fields'];
        $dataAggregations = $lib['data_aggregations'];
        $rowFields = $lib['row_fields'];
        $filters = $lib['filters'];
        $dataAggregations =ReportPivot::combineArrays($dataFields, $dataAggregations);
        return [$rowFields,$filters, $dataAggregations];
    }

    protected function makeDataOutput($columnFields)
    {
        [$rowFields,$filters, $dataAggregations] =  $this->getDataFields();
        $valueFilters = array_combine($filters, [[1, 4], [7, 8]]);
        // Step 1: reduce data from Filters columns
        $linesData = $this->getDataSource();
        $dataReduce = self::reduceData($linesData, $valueFilters);
        // dd($valueFilters, $dataReduce);

        // Step 2: group data from Row Fields columns
        $dataProcess = self::processData($dataReduce, $rowFields);
        $dataProcess = array_values(array_map(fn ($item) => ReportPivot::getLastArray($item), $dataProcess));
        // dd($dataProcess);
        // Step 3: transfer data from Column Fields columns
        $transferData = $this->transferData($dataProcess, $columnFields);

        //Step 4: Calculate data from Data Fields columns
        $dataCalculation = array_map(fn ($items) => ReportPivotDataFields::executeFunctions($dataAggregations, $items), $transferData);
        $dataOutput = ReportPivot::mergeChildrenValue($dataCalculation);
        $titles = array_column($columnFields, 'title');
        // dd(132);

        array_walk($dataOutput, function ($item, $key) use (&$dataOutput, $columnFields, $titles) {
            $dates = Report::retrieveDataByIndex($item, 'pj_task_name');
            // check type to render
            foreach ($columnFields as $columnField) {
                $isValidDate =  ReportPivot::isValidDate($item[$columnField['fieldIndex']]);
                if ($isValidDate) {
                    $datesHaveValue = [];
                    foreach (array_keys($dates) as $date) if (is_numeric(str_replace('_', '', $date))) $datesHaveValue[] = $date;
                    $strDates = $this->getStringDates('');
                    // $diffArray = array_diff($strDates, $datesHaveValue);
                    // $emptyDates = array_merge(...array_values(array_map(function ($item) use ($titles) {
                    //     return array_merge([$item], array_map(fn ($title) => $item . '_' . $title, $titles));
                    // }, $diffArray)));
                    // // $emptyDates = array_fill_keys(array_values($emptyDates), '');
                    $dataOutput[$key] = $item + $emptyDates;
                }
            }
        });
        $group = Report::groupArrayByKey($dataOutput, $rowFields[0]);
        ksort($group);
        $dataSource = [];
        foreach ($group as $value ){
           $dataSource = array_merge($dataSource,collect($value)->sortBy($rowFields[1])->toArray());
        }
        // dd($dataSource);
        return collect($dataSource);
    }

    public function index(Request $request)
    {
        $columnFields = $this->makeColumnFields();
        $dataOutput = $this->makeDataOutput($columnFields);
        $tableColumns = $this->makeColumns($dataOutput, $columnFields);
        return view("welcome-due", [
            'tableDataSource' => $dataOutput,
            'tableColumns' => $tableColumns
        ]);
    }
}
