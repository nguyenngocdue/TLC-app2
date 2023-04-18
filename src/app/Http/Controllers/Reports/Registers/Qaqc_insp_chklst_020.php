<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentRegisterController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Sub_project;
use App\Utils\Support\Report;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Qaqc_insp_chklst_020 extends Report_ParentRegisterController
{
    use TraitDynamicColumnsTableReport;
    use TraitModifyDataToExcelReport;
    use TraitForwardModeReport;


    protected $rotate45Width = 300;
    protected  $sub_project_id = 82;
    protected  $prod_routing_id = 6;
    protected $mode = '020';

    public function getSqlStr($modeParams)
    {
        $sql = "SELECT 
        po_tb.*
        ,tmpl.id AS tmpl_id
        ,tmpl.name AS tmpl_name
        ,tmplsh.description AS tmplsh_desc
        ,chklst_shts.id AS chklst_shts_id
        ,chklst_shts.description AS chklst_shts_desc
        ,chklst_shts.status AS chklst_shts_status
        
        FROM (SELECT
        pr.id AS project_id
        ,pr.name AS project_name
        ,sp.id AS sub_project_id
        ,sp.name AS sub_project_name
        ,po.id AS prod_order_id
        ,po.name AS prod_order_name
        ,pr.id AS prod_routing_id
        ,pr.name AS prod_routing_name
        FROM projects pj, sub_projects sp, prod_orders po, prod_routings pr
        WHERE 1 = 1
        AND pj.id = sp.project_id
        AND po.sub_project_id = sp.id
        #AND po.id = 267

        AND pr.id = po.prod_routing_id";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND po.sub_project_id = '{{sub_project_id}}'";
        if (isset($modeParams['prod_routing_id'])) $sql .= "\n AND pr.id = '{{prod_routing_id}}'";
        // AND po.id = 230
        // AND pr.id = 6
        // AND sp.id = 82
        $sql .= "\n ) AS po_tb
              LEFT JOIN qaqc_insp_chklsts chksh ON chksh.prod_order_id = po_tb.prod_order_id #AND chksh.id = 3
             LEFT JOIN qaqc_insp_tmpls tmpl ON tmpl.id = chksh.qaqc_insp_tmpl_id";
        if (isset($modeParams['checksheet_type_id'])) $sql .= "\n AND tmpl.id = '{{checksheet_type_id}}'";

        $sql .= "\n LEFT JOIN qaqc_insp_tmpl_shts tmplsh ON tmplsh.qaqc_insp_tmpl_id =  tmpl.id
             LEFT JOIN qaqc_insp_chklst_shts chklst_shts ON chklst_shts.qaqc_insp_tmpl_sht_id = tmplsh.id AND chksh.id = chklst_shts.qaqc_insp_chklst_id
        ORDER BY prod_order_name;";
        return $sql;
    }
    public function getTableColumns($dataSource, $modeParams)
    {
        $items = $dataSource instanceof Collection ? array_map(fn ($i) => (array)$i, $dataSource->toArray()) : $dataSource->items();
        $flattenData = array_merge(...$items);
        $idx = array_search("chklst_shts_status", array_keys($flattenData));
        $dataColumn = array_slice($flattenData, $idx + 2, count($flattenData) - $idx, true);
        ksort($dataColumn);
        $adds = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => "center",
            ],
            [
                "title" => "Prod Order Name",
                "dataIndex" => "prod_order_name",
                "align" => "center",
            ],
        ];
        $sqlCol =  array_map(fn ($item) => ["dataIndex" => $item, "align" => "center", "width" => 100], array_keys($dataColumn));
        $dataColumn = array_merge($adds, $sqlCol);
        return $dataColumn;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Checklist Type',
                'dataIndex' => 'checksheet_type_id',
                'allowClear' => true
            ],
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
                'allowClear' => true
            ],
            [
                'title' => 'Prod Routing',
                'dataIndex' => 'prod_routing_id',
                'allowClear' => true
            ],
        ];
    }

    protected function filterDataFromProdRouting($modeParams)
    {
        $sql = "SELECT
                        pr.id AS prod_routing_id,
                        pr.name AS prod_routing_name,
                        tmplsh.id AS prod_routing_desc,
                        tmplsh.description AS prod_routing_desc
                        FROM prod_routings pr, qaqc_insp_tmpls tmpl, qaqc_insp_tmpl_shts tmplsh
                        WHERE 1 = 1
                        AND tmpl.prod_routing_id = pr.id
                        AND tmplsh.qaqc_insp_tmpl_id = tmpl.id \n";
        $sql  .= isset($modeParams['prod_routing_id']) && !is_null($modeParams['prod_routing_id']) ? "\n AND pr.id =" . $modeParams["prod_routing_id"] : "";
        $sql  .= isset($modeParams['checksheet_type_id']) && !is_null($modeParams['checksheet_type_id']) ? "\n AND tmpl.id =" . $modeParams["checksheet_type_id"] : "";
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }


    protected function transformDataSource($dataSource, $modeParams)
    {
        // dd($dataSource);
        $dataRouting = $this->filterDataFromProdRouting($modeParams);
        // dd($dataRouting);
        $routingDesc = array_column($dataRouting, 'prod_routing_desc');
        $routingDesc = array_merge(...array_map(fn ($item) => [Report::slugName($item) => null], $routingDesc));

        $transformData = array_map(function ($item) {
            if (!is_null($item->chklst_shts_desc)) {
                $sheetNameKey = Report::slugName($item->chklst_shts_desc);
                return (array)$item + [$sheetNameKey => $item->chklst_shts_status];
            }
            return (array)$item;
        }, $dataSource->ToArray());
        // dd($transformData);
        $groupedArray = Report::groupArrayByKey($transformData, 'prod_order_id');
        $dataSource = Report::mergeArrayValues($groupedArray);

        array_walk($dataSource, function ($item, $key) use (&$dataSource, $routingDesc) {
            if (!is_null($item['chklst_shts_desc'])) {
                $itemHasNotShts = array_diff_key($routingDesc, $item);
                $dataSource[$key] = $item + $itemHasNotShts;
            }
            $dataSource[$key] = $item + $routingDesc;
        });
        return collect($dataSource);
    }

    protected function changeValueData($dataSource)
    {
        $items = $dataSource->toArray();
        foreach ($items as $key => $value) {
            $idx = array_search("chklst_shts_status", array_keys($value));
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
                    case null:
                        $value[$col] = (object)[
                            'value' => '<i class="fa-sharp fa-regular fa-circle"></i>',
                            'cell_class' => 'bg-gray-400',
                            'cell_title' => "Not Yet Started",
                        ];
                        break;
                    default:
                        break;
                }
            }
            $items[$key] = $value;
            $items[$key]['prod_order_name'] = (object)['value' => $value['prod_order_name'], 'cell_title' => 'ID: ' . $value['prod_order_id']];
        }
        return collect($items);
    }


    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $x = 'sub_project_id';
        $y = 'prod_routing_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->sub_project_id;
            $modeParams[$y] = $this->prod_routing_id;
        }
        return $modeParams;
    }
}
