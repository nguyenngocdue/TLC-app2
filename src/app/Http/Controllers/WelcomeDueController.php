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
                "staff_id" => "TLCM01071",
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
                "staff_id" => "TLCM01071",
            ],
            [
                "time_sheet_start_time" => "2022-10-20",
                "time_sheet_start_time_otr" => "2022-10-20",
                "time_sheet_start_time_wfh" => "2022-10-20",
                "time_sheet_hours" => 8.0,
                "time_sheet_hours_otr" => 2,
                "time_sheet_hours_wfh" => 7,
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
                "staff_id" => "TLCM01071",
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
                "staff_id" => "TLCM01071",
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
                "staff_id" => "TLCM01060",
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
                "staff_id" => "TLCM01060",
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
                "category_id" => 42,
                "staff_id" => "TLCM01060",
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
                "type_id" => 1,
                "department_id" => 7,
                "category_id" => 3,
                "staff_id" => "TLCM01219",
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
                "type_id" => 1,
                "department_id" => 7,
                "category_id" => 3,
                "staff_id" => "TLCM01219",
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
                "type_id" => 1,
                "department_id" => 7,
                "category_id" => 3,
                "staff_id" => "TLCM01219",
            ]
        ];
        return $dataSource;
    }


    private function groupBy($lineData, $rowFields)
    {
        $dataOutput = [];
        foreach ($lineData as $line) {
            // Get the values of fields in $rowFields
            $params = [];
            foreach ($rowFields as $param) {
                if (isset($line[$param])) $params[$param] = $line[$param];
                else $params[$param] = null;
            }
            $nestedArray = &$dataOutput;
            foreach ($params as $paramValue) {
                // Create nested arrays in $dataOutput based on the values of fields in $rowFields
                if (!isset($nestedArray[$paramValue])) {
                    $nestedArray[$paramValue] = [];
                }
                $nestedArray = &$nestedArray[$paramValue];
            }
            if (!isset($nestedArray['items'])) $nestedArray['items'] = [];
            $nestedArray['items'][] = $line;
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
        $data = array_map(
            fn ($items) => array_map(
                fn ($array) =>
                ReportPivot::transferValueOfKeys($array, $columnFields),
                $items
            ),
            $dataSource
        );
        return $data;
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

    private function makeHeadColumn($rowFields)
    {
        return array_map(fn ($item) => [
            'dataIndex' => $item,
            'width' => 150,
        ], $rowFields);
    }
    protected function makeColumnFields()
    {
        return [[]];
    }


    private function makeColumns($dataOutput)
    {

        [$rowFields,,, $dataAggregations] =  $this->getDataFields();
        $columnsOfRowFields = $this->makeHeadColumn($rowFields);
        $col = [];
        foreach ($dataOutput as $value) $col = array_unique(array_merge($col, array_keys($value)));

        $lastItemDataSource = key(array_slice($this->getDataSource()[0] ?? [],-1)) ;
        $fieldDates = Report::retrieveDataByIndex($col, $lastItemDataSource, false,'value');
        $columnsOfColumnFields = array_map(fn ($item) => [
            'dataIndex' => $item,
            'align' => 'center',
            'width' => 40
        ], $fieldDates);

        $columnsOfAgg = [];
        foreach ($dataAggregations as $filed => $fn) {
            $columnsOfAgg[] = [
                'dataIndex' => $fn . '_' . $filed,
                'align' => 'right',
                'width' => 40
            ];
        };
        $tableColumns = array_merge($columnsOfRowFields, $columnsOfColumnFields, $columnsOfAgg);
        return $tableColumns;
    }

    private function getDataFields()
    {
        $lib = LibPivotTables::getFor($this->modeType);
        $dataFields = $lib['data_fields'];
        $rowFields = $lib['row_fields'];
        $valueIndexFields = $lib['value_index_fields'];

        //Map Column Fields & Value Index Fields
        $filters = $lib['filters'];
        $_columnFields = $lib['column_fields'];
        $columnFields = [];
        foreach ($_columnFields as $key => $filed) {
            $columnFields[] = [
                'fieldIndex' => $filed,
                'valueFieldIndex' => $valueIndexFields[$key] ?? dd('Empty Value Index Fields')
            ];
        }

        $dataAggregations = ReportPivot::combineArrays($dataFields, $lib['data_aggregations']);
        return [$rowFields, $filters, $columnFields, $dataAggregations];
    }

private function attachToDataSource($processedData, $calculatedData, $transferredData){
    $dataOutput = [];
    foreach ($processedData as $k1 => $items) {
        foreach ($items as $k2 => $item) $dataOutput[] = $calculatedData[$k1][$k2] + reset($item)+ $transferredData[$k1][$k2];
    }
    return $dataOutput;
}

    protected function makeDataOutput()
    {
        [$rowFields, $filters, $columnFields, $dataAggregations] =  $this->getDataFields();
        $valueFilters = array_combine($filters, [[1, 4], [7, 8]]);
        // Step 1: reduce lines from Filters array
        $linesData = $this->getDataSource();
        $dataReduce = self::reduceData($linesData, $valueFilters);
        // dd($valueFilters, $dataReduce);

        // Step 2: group lines by Row_Fields array
        $processedData = self::groupBy($dataReduce, $rowFields);
        //Remove all array keys by looping through all elements
        $processedData = array_values(array_map(fn ($item) => ReportPivot::getLastArray($item), $processedData));
        // dd($processedData);

        // Step 3: transfer data from lines to columns by
        // Column_Fields and Value_Index_Fields array 
        $transferredData = $this->transferData($processedData, $columnFields);
        // dd($transferData);

        //Step 4: Calculate data from Data Fields columns
        //The aggregated data are at the end of the items
        $calculatedData = array_map(fn ($items) => ReportPivotDataFields::executeOperations($dataAggregations, $items), $processedData);

        $dataOutput = $this->attachToDataSource($processedData, $calculatedData, $transferredData);

        $group = Report::groupArrayByKey($dataOutput, $rowFields[0]);
        ksort($group);
        $dataSource = [];
        foreach ($group as $value) {
            $dataSource = array_merge($dataSource, collect($value)->sortBy($rowFields[1])->toArray());
        }
        return collect($dataSource);
    }

    public function index(Request $request)
    {
        $dataOutput = $this->makeDataOutput();
        $tableColumns = $this->makeColumns($dataOutput);
        return view("welcome-due", [
            'tableDataSource' => $dataOutput,
            'tableColumns' => $tableColumns
        ]);
    }
}
