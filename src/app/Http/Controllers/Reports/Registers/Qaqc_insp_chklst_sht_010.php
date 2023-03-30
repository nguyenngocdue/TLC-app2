<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\UpdateUserSettings;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Sub_project;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Log;

class Qaqc_insp_chklst_sht_010 extends Report_ParentController
{
    use TraitDynamicColumnsTableReport;

    protected $rotate45Width = 300;
    // set default params's values 
    protected  $sub_project_id = 21;

    public function getSqlStr($modeParams)
    {
        $sql = "SELECT tb.*,
        CASE
            WHEN sheet_status_combine LIKE '%No%' THEN 'Inprogress'
            WHEN sheet_status_combine LIKE '%Fail%' THEN 'Inprogress'
            WHEN sheet_status_combine LIKE'%N/A%'  THEN 'Inprogress'
            WHEN sheet_status_combine LIKE '%Yes%' OR '%Pass%' THEN 'Pass'
            WHEN sheet_status_combine LIKE 'N/A' THEN 'NA'
            WHEN sheet_status_combine LIKE 'On Hold' THEN 'OnHold'
            ELSE 'Null'
        END AS sheet_status
    FROM (SELECT 
        sub_project,prod_id,prod_name
        ,tmpl_sheet_id
        ,tmpl_sheet_description
        ,sheet_id
        ,max_run_id
        ,GROUP_CONCAT(DISTINCT cv.name) AS sheet_status_combine
        
        FROM (SELECT
            sub_project,prod_id, prod_name
            ,tmpl_sheet_id
            ,tmpl_sheet_description
            ,sheet_id
            ,MAX(rs.id) AS max_run_id
        FROM (SELECT
             sub_project
             ,prod_id
             ,prod_name
            ,tmpl_sheet_description
            ,tmpl_sheet_id
            ,csh.id AS sheet_id
            ,csh.description AS sheet_description
            ,template_id
            FROM (SELECT 
                    prod.sub_project_id AS sub_project
                    ,prod.id AS prod_id
                    ,prod.name AS prod_name
                    ,tmpl.id AS template_id
                    ,tmplsh.id AS tmpl_sheet_id
                    ,tmplsh.description AS tmpl_sheet_description
                    , chlst.id AS check_list_id
                    FROM prod_orders prod, qaqc_insp_chklsts chlst, qaqc_insp_tmpls tmpl, qaqc_insp_tmpl_shts tmplsh
                    WHERE 1 = 1";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND prod.sub_project_id = '{{sub_project_id}}'";
        if (isset($modeParams['qaqc_insp_tmpl_id'])) $sql .= "\n AND chlst.qaqc_insp_tmpl_id = '{{qaqc_insp_tmpl_id}}'";
        $sql .= "\n AND chlst.qaqc_insp_tmpl_id = 1
                    AND chlst.prod_order_id = prod.id
                    AND chlst.qaqc_insp_tmpl_id = tmpl.id
                    AND tmplsh.qaqc_insp_tmpl_id = tmpl.id
                 ) AS temptb
              LEFT JOIN qaqc_insp_chklst_shts csh ON temptb.tmpl_sheet_id = csh.qaqc_insp_tmpl_sht_id AND csh.qaqc_insp_chklst_id  = temptb.check_list_id) AS chklst_shts
              LEFT JOIN qaqc_insp_chklst_runs rs ON rs.qaqc_insp_chklst_sht_id = chklst_shts.sheet_id
              GROUP BY chklst_shts.sheet_id, tmpl_sheet_id, tmpl_sheet_description, prod_id ) AS maxRuntb
    
        LEFT JOIN qaqc_insp_chklst_lines lr ON lr.qaqc_insp_chklst_run_id = max_run_id
        LEFT JOIN qaqc_insp_control_values cv ON cv.id = lr.qaqc_insp_control_value_id
        LEFT JOIN control_types ct ON ct.id = lr.control_type_id
        GROUP BY sheet_id, prod_id, tmpl_sheet_id) AS tb";
        return $sql;
    }
    public function getTableColumns($dataSource, $modeParams)
    {
        $array = [];
        foreach ($dataSource as $key => $value) {
            $array[] = (array)$value;
        }
        $dataSource = $array;


        $flattenData = array_merge(...$dataSource);
        $idx = array_search("sheet_status", array_keys($flattenData));

        $dataColumn = array_slice($flattenData, $idx + 1, count($flattenData) - $idx, true);
        ksort($dataColumn);

        $adds = [
            [
                "dataIndex" => "prod_id",
                "align" => "center"
            ],            [
                "dataIndex" => "prod_name",
                "align" => "center"
            ]
        ];
        $sqlCol =  array_map(fn ($item) => ["dataIndex" => $item, "align" => "center"], array_keys($dataColumn));
        $dataColumn = array_merge($adds, $sqlCol);
        return  $dataColumn;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
            ],
            [
                'title' => 'Checklist Type',
                'dataIndex' => 'qaqc_insp_tmpl_id',
                'allowClear' => true
            ]
        ];
    }

    private function changeValueData($dataSource)
    {

        foreach ($dataSource as $key => $value) {
            $idx = array_search("sheet_status", array_keys($value));
            $rangeArray = array_slice($value, $idx + 1, count($value) - $idx, true);
            foreach ($rangeArray as $col => $valCol) {
                switch ($valCol) {
                    case "Pass":
                        $value[$col] = (object)[
                            'value' => '<i class="fa-solid fa-circle-check"></i>',
                            'cell_class' => 'bg-green-400',
                            'cell_title' => "Pass",
                        ];
                        break;
                    case "Inprogress":
                        $value[$col] = (object)[
                            'value' => '<i class="fa-sharp fa-regular fa-circle-stop"></i>',
                            'cell_class' => 'bg-orange-400',
                            'cell_title' => "In Progress",
                        ];
                        break;
                    case "OnHold":
                        $value[$col] = (object)[
                            'value' => '<i class="fa-sharp fa-solid fa-circle-euro"></i>',
                            'cell_class' => 'bg-orange-600',
                            'cell_title' => "On Hold",
                        ];
                        break;
                    case "NA":
                        $value[$col] = (object)[
                            'value' => '<i class="fa-sharp fa-regular fa-circle"></i>',
                            'cell_class' => 'bg-blue-400',
                            'cell_title' => "Not Applicable",
                        ];
                        break;
                    case "Null":
                        $value[$col] = (object)[
                            'value' => '<i class="fa-sharp fa-regular fa-circle"></i>',
                            'cell_class' => 'bg-gray-400',
                            'cell_title' => "Work Not Started",
                        ];
                        break;
                    default:
                        break;
                }
            }
            $dataSource[$key] = $value;
        }
        return $dataSource;
    }

    public function getDataForModeControl($dataSource = [])
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        $insp_tmpls = ['qaqc_insp_tmpl_id' => Qaqc_insp_tmpl::get()->pluck('name', 'id')->toArray()];
        return array_merge($subProjects, $insp_tmpls);
    }

    protected function enrichDataSource($dataSource, $modeParams)
    {
        $isNullParams = Report::isNullModeParams($modeParams);
        if ($isNullParams) return collect([]);

        $enrichData = array_map(function ($item) {
            return (array)$item + [Report::slugName($item->tmpl_sheet_description) => $item->sheet_status];
        }, $dataSource->ToArray());

        $groupedArray = Report::groupArrayByKey($enrichData, 'prod_id');
        $result = Report::mergeArrayValues($groupedArray);
        $data = $this->changeValueData($result);
        // dd($data);
        return collect($data);
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $x = 'sub_project_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->sub_project_id;
        }
        return $modeParams;
    }
}
