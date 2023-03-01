<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Helpers\Helper;
use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Sub_project;
use App\Utils\Support\Report;

class Qaqc_insp_chklst_sht extends Report_ParentController
{
    use TraitReport;
    protected $pagingSize = 10;
    public function getSqlStr($urlParams)
    {
        // dd($urlParams);
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
        if (isset($urlParams['sub_project_id'])) $sql .= "\n AND prod.sub_project_id = '{{sub_project_id}}'";
        if (isset($urlParams['qaqc_insp_tmpl_id'])) $sql .= "\n AND chlst.qaqc_insp_tmpl_id = '{{qaqc_insp_tmpl_id}}'";
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
    public function getTableColumns($dataSource)
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
    private function changeValueData($dataSource)
    {
        $iconPass = '<div class="bg-green-400"><i class="fa-solid fa-circle-check" title="Pass"></i> </div>';
        $iconInprogress = '<div class="bg-orange-400"><i class="fa-sharp fa-regular fa-circle-stop" title="Inprogress"></i></di>';
        $iconOnHold = '<div class="bg-orange-600"><i class="fa-sharp fa-solid fa-circle-euro" title="On Hold"></i></di>';
        $iconNA = '<div class="bg-blue-400"><i class="fa-sharp fa-regular fa-circle" title="Na"></i></di>';
        $iconNull = '<div class="bg-gray-400"><i class="fa-sharp fa-regular fa-circle" title="Null"></i></di>';

        foreach ($dataSource as $key => $value) {
            $idx = array_search("sheet_status", array_keys($value));
            $rangeArray = array_slice($value, $idx + 1, count($value) - $idx, true);
            foreach ($rangeArray as $col => $valCol) {
                switch ($valCol) {
                    case "Pass":
                        $value[$col] = $iconPass;
                        break;
                    case "Inprogress":
                        $value[$col] = $iconInprogress;
                        break;
                    case "OnHold":
                        $value[$col] = $iconOnHold;
                        break;
                    case "NA":
                        $value[$col] = $iconNA;
                        break;
                    case "Null":
                        $value[$col] = $iconNull;
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

    protected function enrichDataSource($dataSource, $urlParams)
    {
        $isNullParams = $this->isNullUrlParams($urlParams);
        if ($isNullParams) return collect([]);

        $enrichData = array_map(function ($item) {
            return (array)$item + [Report::slugName($item->tmpl_sheet_description) => $item->sheet_status];
        }, $dataSource->ToArray());

        $groupedArray = Report::groupArrayByKey($enrichData, 'prod_id');
        $result = Report::mergeArrayValues($groupedArray);
        $data = $this->changeValueData($result);
        return collect($data);
    }
}
