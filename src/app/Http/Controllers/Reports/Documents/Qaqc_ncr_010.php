<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Qaqc_ncr_dataSource;
use App\Http\Controllers\Workflow\LibApps;
use App\Models\Qaqc_ncr;
use App\Utils\Support\ArrayReport;
use App\Utils\Support\Report;

class Qaqc_ncr_010 extends Report_ParentDocument2Controller
{

    protected $mode = '010';
    protected $projectId = 5;
    protected $viewName = 'document-ncr-010';

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Date',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
                "allowClear" => false,
                'validation' => 'date_format:d/m/Y',
            ],
            [
                "title" => "Project",
                "dataIndex" => "project_id",
                "allowClear" => true,
            ],
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_id",
                "hasListenTo" => true,
                // "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Production Routing",
                "dataIndex" => "prod_routing_id",
                "hasListenTo" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Production Order",
                "dataIndex" => "prod_order_id",
                "multiple" => true,
                "hasListenTo" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Production Discipline",
                "dataIndex" => "prod_discipline_id",
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Responsible Team",
                "dataIndex" => "user_team_ncr",
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Root Cause",
                "dataIndex" => "root_cause",
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Report Type",
                "dataIndex" => "report_type",
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Status",
                "dataIndex" => "status",
                "multiple" => true,
                "allowClear" => true,
            ],
        ];
    }

    public function getTableColumns($params, $dataSource)
    {
        return [
        ];
    }

    public function getDataSource($params)
    {
        $primaryData = new Qaqc_ncr_dataSource();
        $_primaryData = $primaryData->getDataSource($params);
        $primaryData = $primaryData->changeDataSource($_primaryData, $params);
        return $primaryData;
    }

    public function changeDataSource($dataSource, $params)
    {        
        $dataSource = Report::convertToType($dataSource);
        $groupByStatuses = Report::groupArrayByKey($dataSource, 'ncr_status');
        $numberOfStatuses = Report::countValuesInArray($groupByStatuses);

        $groupByPriority= Report::groupArrayByKey($dataSource, 'priority_name');
        $numberOfPriority= Report::countValuesInArray($groupByPriority);


        $groupBySeverity= Report::groupArrayByKey($dataSource, 'severity_name');
        $numberOfSeverity= Report::countValuesInArray($groupBySeverity);

        $groupByReportType= Report::groupArrayByKey($dataSource, 'report_type_name');
        $numberOfReportType= Report::countValuesInArray($groupByReportType);

        $groupByUserTeam= Report::groupArrayByKey($dataSource, 'user_team_name');
        $numberOfUserTeam= Report::countValuesInArray($groupByUserTeam);

        $groupByParentType= Report::groupArrayByKey($dataSource, 'parent_type');
        $numberOfParentType= Report::countValuesInArray($groupByParentType);

        $groupByDiscipline= Report::groupArrayByKey($dataSource, 'prod_discipline_name');
        ksort($groupByDiscipline);
        $numberOfDiscipline= Report::countValuesInArray($groupByDiscipline);
        
        $groupByInterSubconName= Report::groupArrayByKey($dataSource, 'inter_subcon_name');
        $numberOfInterSubconName= Report::countValuesInArray($groupByInterSubconName);

        $groupByRoutCause= Report::groupArrayByKey($dataSource, 'root_cause_name');
        $numberOfRoutCause= Report::countValuesInArray($groupByRoutCause);

        $groupByDisposition= Report::groupArrayByKey($dataSource, 'disposition_name');
        $numberOfDisposition= Report::countValuesInArray($groupByDisposition);

        $data = [
            'tableDataSource' => collect($dataSource),
            'widgets' =>[
                    'ncr_status' => $numberOfStatuses, 
                    'ncr_priority' => $numberOfPriority,
                    'ncr_severity' => $numberOfSeverity, 
                    'ncr_user_team' => $numberOfUserTeam, 
                    'ncr_parent_type' => $numberOfParentType, 
                    'ncr_report_type' => $numberOfReportType,
                    'ncr_discipline' => $numberOfDiscipline,
                    'ncr_inter_subcon' => $numberOfInterSubconName,
                    'ncr_root_cause' => $numberOfRoutCause,
                    'ncr_disposition' => $numberOfDisposition]
        ];
        // dd($data);
        return $data;
    }
}
