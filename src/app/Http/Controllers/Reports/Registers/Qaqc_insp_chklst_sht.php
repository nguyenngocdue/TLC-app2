<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Helpers\Helper;
use App\Http\Controllers\Reports\Report_ParentController;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Sub_project;
use App\Utils\Support\Report;

class Qaqc_insp_chklst_sht extends Report_ParentController
{
    public function getSqlStr($urlParams)
    {
        // dd($urlParams);
        $sql = " SELECT *, 
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
                            po.sub_project_id AS sub_project
                            ,po.id AS po_id
                            ,po.name AS po_name
                            ,csh.id AS check_sheet_id
                            ,sh.id AS sheet_id
                            ,sh.description AS sheet_desc
                            ,GROUP_CONCAT(DISTINCT cv.name) AS sheet_status_combine
                        
                        FROM (
                        SELECT
                            sh.id AS sheet_id,
                            MAX(sr.id) AS max_run_id
                            FROM prod_orders po, qaqc_insp_chklsts csh, qaqc_insp_chklst_shts sh, qaqc_insp_chklst_runs sr,  qaqc_insp_tmpls tmpl
                                WHERE 1 = 1
                                AND csh.qaqc_insp_tmpl_id = tmpl.id";

        if (isset($urlParams['qaqc_insp_tmpl_id'])) $sql .= " \n AND tmpl.id = {{qaqc_insp_tmpl_id}} \n";
        if (isset($urlParams['sub_project_id'])) $sql .= " \n AND po.sub_project_id = {{sub_project_id}} \n";
        $sql .= "\n AND po.id = csh.prod_order_id
                                AND csh.id = sh.qaqc_insp_chklst_id
                                AND sh.id = sr.qaqc_insp_chklst_sht_id
                                GROUP BY sh.id
                        ) sub
                        JOIN qaqc_insp_chklst_runs sr ON sr.id = sub.max_run_id
                        JOIN qaqc_insp_chklst_shts sh ON sh.id = sr.qaqc_insp_chklst_sht_id
                        JOIN qaqc_insp_chklsts csh ON csh.id = sh.qaqc_insp_chklst_id
                        LEFT JOIN prod_orders po ON po.id = csh.prod_order_id
                        JOIN qaqc_insp_chklst_lines lr ON lr.qaqc_insp_chklst_run_id = sr.id
                        LEFT JOIN qaqc_insp_control_values cv ON cv.id = lr.qaqc_insp_control_value_id
                        JOIN control_types ct ON ct.id = lr.control_type_id
                        GROUP BY sh.id
                        ) AS tb";
        // dump($sql);
        return $sql;
    }
    public function getTableColumns($dataSource = [])
    {
        $flattenData = array_merge(...$dataSource);
        $idx = array_search("sheet_status", array_keys($flattenData));
        $dataColumn = array_slice($flattenData, $idx + 1, count($flattenData) - $idx, true);
        // dd($dataSource);
        $adds = [
            [
                "dataIndex" => "po_id",
                "align" => "center"
            ],            [
                "dataIndex" => "po_name",
                "align" => "center"
            ]
        ];
        $cols = $adds + array_map(fn ($item) => ["dataIndex" => $item, "align" => "center"], array_keys($dataColumn));
        return  $cols;
    }

    protected function enrichDataSource($dataSource, $urlParams)
    {
        if (!is_array($dataSource)) return [];
        $enrichData = array_map(function ($item) {
            return $item + [Report::slugName($item['sheet_desc']) => $item['sheet_status']];
        }, array_values($dataSource));

        $groupedArray = Report::groupArrayByKey($enrichData, 'po_id');
        $result = Report::mergeArrayValues($groupedArray);
        $dataSource = $this->changeValueData($result);
        return $dataSource;
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
}
