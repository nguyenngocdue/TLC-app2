<?php

namespace App\Http\Controllers\Reports\Registers;

use App\BigThink\HasStatus;
use App\Http\Controllers\Reports\Report_ParentRegisterController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Qaqc_insp_tmpl_sht;
use App\Utils\Support\Report;
use Illuminate\Support\Str;

class Qaqc_insp_chklst_020 extends Report_ParentRegisterController
{
    use TraitDynamicColumnsTableReport;
    use TraitModifyDataToExcelReport;
    use TraitForwardModeReport;
    use HasStatus;


    protected $rotate45Width = 300;
    protected  $sub_project_id = 82;
    protected  $prod_routing_id = 6;
    protected $mode = '020';
    protected $checksheet_type_id = 1;

    public function getSqlStr($modeParams)
    {
        $sql = "SELECT 
        po_tb.*
        ,tmplsh.description AS tmplsh_desc
        ,chklst_shts.id AS chklst_shts_id
        ,chklst_shts.qaqc_insp_chklst_id AS chklst_shts_chklst_id
        ,chklst_shts.description AS chklst_shts_desc
        ,chksh.id AS chksh_id
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
              LEFT JOIN qaqc_insp_chklsts chksh ON chksh.prod_order_id = po_tb.prod_order_id ";
        if (isset($modeParams['checksheet_type_id'])) $sql .= " AND chksh.id = '{{checksheet_type_id}}'";
        $sql .= "\n LEFT JOIN qaqc_insp_chklst_shts chklst_shts ON chksh.id = chklst_shts.qaqc_insp_chklst_id
              LEFT JOIN qaqc_insp_tmpls tmpl ON tmpl.id = chksh.qaqc_insp_tmpl_id
              LEFT JOIN qaqc_insp_tmpl_shts tmplsh ON tmplsh.id =  chklst_shts.qaqc_insp_tmpl_sht_id AND tmpl.id = tmplsh.qaqc_insp_tmpl_id
              ORDER BY prod_order_name";
        return $sql;
    }
    public function getTableColumns($dataSource, $modeParams)
    {
        $dataColumn1 = [
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

        $sheetsDesc = $this->transformSheetsDesc($modeParams);
        $dataColumn2 = [];
        foreach (array_keys($sheetsDesc) as $value) {
            $dataColumn2[] = [
                "dataIndex" => $value,
                "align" => "center",
            ];
        }
        $dataColumn = array_merge($dataColumn1, $dataColumn2);
        // dd($dataColumn);
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
            ],
            [
                'title' => 'Prod Routing',
                'dataIndex' => 'prod_routing_id',
                'allowClear' => true
            ],
        ];
    }

    protected function filterSheetFromProdRouting($modeParams)
    {
        $checksheet_type_id = $modeParams['checksheet_type_id'];
        $sheets = Qaqc_insp_tmpl::find($checksheet_type_id)->getSheets->pluck('description', 'id')->ToArray();
        return $sheets;
    }

    private function transformSheetsDesc($modeParams)
    {
        $sheets = $this->filterSheetFromProdRouting($modeParams);
        $sheetsDesc = array_merge(...array_map(fn ($item) => [Report::slugName($item) => null], $sheets));
        return $sheetsDesc;
    }


    protected function transformDataSource($dataSource, $modeParams)
    {
        // dd($dataSource);
        $items = $dataSource->toArray();

        // $prodOrders = array_slice(array_unique(array_column($items, 'prod_order_id')), 0, 10);
        // $filteredData = array_filter($items, function ($item) use ($prodOrders) {
        //     return in_array($item->prod_order_id, $prodOrders);
        // });
        // $items = $filteredData;
        // dd($items);


        $sheetsDesc = $this->transformSheetsDesc($modeParams);
        $transformData = array_map(function ($item) {
            if (!is_null($item->chklst_shts_desc)) {
                $sheetNameKey = Report::slugName($item->chklst_shts_desc);
                return (array)$item + [$sheetNameKey => $item->chklst_shts_status];
            }
            return (array)$item;
        }, $items);
        $groupedArray = Report::groupArrayByKey($transformData, 'prod_order_id');
        $dataSource = Report::mergeArrayValues($groupedArray);
        // dd($groupedArray);

        array_walk($dataSource, function ($item, $key) use (&$dataSource, $sheetsDesc) {
            if (!is_null($item['chklst_shts_desc'])) {
                $itemHasNotShts = array_diff_key($sheetsDesc, $item);
                $dataSource[$key] = $item + $itemHasNotShts;
            }
            $dataSource[$key] = $item + $sheetsDesc;
        });
        // dd($dataSource);
        return collect($dataSource);
    }

    protected function changeValueData($dataSource)
    {
        $plural = 'qaqc_insp_chklst_shts';
        $statuses = LibStatuses::getFor($plural);
        $items = $dataSource->toArray();
        foreach ($items as $key => $value) {
            $idx = array_search("chklst_shts_status", array_keys($value));
            $rangeArray = array_slice($value, $idx + 1, count($value) - $idx, true);
            foreach ($rangeArray as $col => $valCol) {
                if (isset($statuses[$valCol])) {
                    $status = $statuses[$valCol];
                    $id = Qaqc_insp_chklst_sht::get()
                        ->where('qaqc_insp_chklst_id', $value['chklst_shts_chklst_id'])
                        ->where('description', Report::replaceAndUcwords($col))
                        ->pluck('id')->toArray()[0] ?? 0;
                    $bgColor = 'bg-' . $status['color'] . '-' . $status['color_index'];
                    $textColor = 'text-' . $status['color'] . '-' . (1000 - $status['color_index']);
                    $value[$col] = (object)[
                        'value' =>  $status["icon"] ?? '<i class="fa-duotone fa-square-question"></i>',
                        'cell_class' => "$bgColor $textColor",
                        'cell_title' => $status['title'],
                        'cell_href' =>  route($plural . '.edit',  $id),
                    ];
                } else {
                    $value[$col] = (object)[
                        'value' =>  '<i class="fa-sharp fa-regular fa-circle"></i>',
                        'cell_class' => 'bg-gray-100 text-gray-300',
                        'cell_title' => 'Not Yet Started'
                    ];
                };
            }
            $items[$key] = $value;
            $items[$key]['prod_order_name'] = (object)['value' => $value['prod_order_name'], 'cell_title' => 'ID: ' . $value['prod_order_id']];
        }
        // dump($items);
        return collect($items);
    }


    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $x = 'sub_project_id';
        $y = 'prod_routing_id';
        $z = 'checksheet_type_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->sub_project_id;
            $modeParams[$y] = $this->prod_routing_id;
            $modeParams[$z] = $this->checksheet_type_id;
        }
        return $modeParams;
    }
}
