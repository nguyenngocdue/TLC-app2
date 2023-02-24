<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Helpers\Helper;
use App\Http\Controllers\Reports\Report_ParentController;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Sub_project;
use App\Utils\Support\Report;

class Qaqc_insp_chklst_sht extends Report_ParentController
{
    protected $pagingSize = 10000;
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
                    max_run_sheetTB.tmpl_shts_id,
                    max_run_sheetTB.tmpl_shts_desc,
                    GROUP_CONCAT(DISTINCT cv.name) AS sheet_status_combine
                FROM (
                    SELECT 
                        cklst_sht_tb.tmpl_shts_id, 
                        cklst_sht_tb.tmpl_shts_desc,
                        MAX(rs.id) AS max_run_id
                        FROM (
                            SELECT
                                tmpls.id AS tmpl_shts_id,
                                tmpls.description AS tmpl_shts_desc,
                                csh.id AS chklsts_shts_id,
                                csh.description AS chklsts_shts_desc
                                FROM qaqc_insp_tmpl_shts tmpls
                                    LEFT JOIN qaqc_insp_chklst_shts csh ON tmpls.id = csh.qaqc_insp_tmpl_sht_id";
        if (isset($urlParams['qaqc_insp_tmpl_id'])) $sql .= "\n AND csh.qaqc_insp_chklst_id  = '{{qaqc_insp_tmpl_id}}'";
        if (isset($urlParams['sub_project_id'])) $sql .= "\n LEFT JOIN prod_orders prod ON prod.sub_project_id  = '{{sub_project_id}}'
                                                            LEFT JOIN qaqc_insp_chklsts clst ON clst.prod_order_id = prod.id AND clst.id = csh.qaqc_insp_chklst_id";

        $sql .= ") AS cklst_sht_tb
                                    LEFT JOIN qaqc_insp_chklst_runs rs ON rs.qaqc_insp_chklst_sht_id = cklst_sht_tb.chklsts_shts_id
                                    GROUP BY cklst_sht_tb.tmpl_shts_id) AS max_run_sheetTB
                    
                LEFT JOIN qaqc_insp_chklst_lines lr ON lr.qaqc_insp_chklst_run_id = max_run_sheetTB.max_run_id
                LEFT JOIN qaqc_insp_control_values cv ON cv.id = lr.qaqc_insp_control_value_id
                LEFT JOIN control_types ct ON ct.id = lr.control_type_id
                GROUP BY max_run_sheetTB.tmpl_shts_id) AS tb;";
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
        $adds = [
            [
                "dataIndex" => "po_id",
                "align" => "center"
            ],            [
                "dataIndex" => "po_name",
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
        // dump($dataSource);
        // if (!count($urlParams)) return $dataSource->setCollection(collect([]));
        if (!count(array_values($urlParams))) return [];
        $dataArray = $dataSource->items();
        $enrichData = array_map(function ($item) {
            return (array)$item + [Report::slugName($item->tmpl_shts_desc) => $item->sheet_status];
        }, array_values($dataArray));


        $groupedArray = Report::groupArrayByKey($enrichData, 'po_id');
        $result = Report::mergeArrayValues($groupedArray);
        $dt = $this->changeValueData($result);
        $dataSource->setCollection(collect($dt));
        dump($dataSource);
        return $dt;
    }
}
