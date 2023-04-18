<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentRegisterController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Report;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Qaqc_wir_010 extends Report_ParentRegisterController
{
    use TraitDynamicColumnsTableReport;
    use TraitFunctionsReport;
    use TraitModifyDataToExcelReport;

    protected $rotate45Width = 500;
    protected $maxH = 80;
    protected  $sub_project_id = 21;
    protected  $prod_routing_id = 2;


    public function getSqlStr($modeParams)
    {
        $sql = "SELECT 
        wirPo.*
        ,wir.id AS wir_id
        ,wir.doc_id AS wir_doc_id
        ,wir.prod_discipline_id AS wir_prod_discipline_id
        ,wir.status AS wir_status
        ,wir.wir_description_id AS wir_description_id
        ,wirdes.name AS wir_description_name
            FROM(SELECT 
                sp.id AS sub_project_id
                ,sp.project_id AS project_id
                ,sp.name AS sub_project_name
                ,po.id AS prod_order_id
                ,po.name AS prod_order_name 
                ,pr.id AS prod_routing_id
                ,pr.name AS prod_routing_name
                FROM  sub_projects sp, prod_orders po, prod_routings pr
                    WHERE 1 = 1
                    AND sp.id = po.sub_project_id
                    AND po.prod_routing_id = pr.id
                    AND pr.id = po.prod_routing_id";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id = '{{sub_project_id}}'";
        if (isset($modeParams['prod_routing_id'])) $sql .= "\n AND pr.id = '{{prod_routing_id}}'";
        $sql .= "\n AND po.prod_routing_id = pr.id) wirPo
                    LEFT JOIN qaqc_wirs wir ON wirPo.prod_order_id = wir.prod_order_id AND wirPo.sub_project_id = wir.sub_project_id 
                    LEFT JOIN wir_descriptions wirdes ON wir.wir_description_id = wirdes.id 
                    ORDER BY wirPo.prod_order_name";

        return $sql;
    }

    protected function getTableColumns($dataSource, $modeParams)
    {
        $items = $dataSource instanceof Collection ? array_map(fn ($i) => (array)$i, $dataSource->toArray()) : $dataSource->items();
        // create table default columns
        if (empty($items)) {
            $dataProdRouting = $this->filterWirDescriptionsFromProdRouting($modeParams);
            $wirDesc = array_values(array_column($dataProdRouting, 'wir_description_name', "wir_description_id"));
            $dataColumn = array_map(fn ($item) => ["dataIndex" => $item, "align" => "center", "width" => 100], $wirDesc);
            return $dataColumn;
        }
        $flattenData = array_merge(...$items);
        // dd($flattenData);
        $idx = array_search("wir_status", array_keys($flattenData));
        $dataColumn = array_slice($flattenData, $idx + 1, count($flattenData) - $idx, true);
        ksort($dataColumn);
        unset($dataColumn['wir_status']);

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
        // dd($dataColumn);
        unset($dataColumn['wir_description_id'], $dataColumn['wir_description_name']);
        $sqlCol =  array_map(fn ($item) => ["dataIndex" => $item, "align" => "center", "width" => 100], array_keys($dataColumn));
        $dataColumn = array_merge($adds, $sqlCol);
        return  $dataColumn;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Sub Porject',
                'dataIndex' => 'sub_project_id',
            ],
            [
                'title' => 'Prod Routing',
                'dataIndex' => 'prod_routing_id',
            ]
        ];
    }

    protected function filterWirDescriptionsFromProdRouting($modeParams)
    {

        $sql = "SELECT wd.id as wir_description_id, wd.name AS wir_description_name
        FROM many_to_many m2m, wir_descriptions wd
        WHERE 1 =1 
        AND doc_type='App\\\Models\\\Wir_description'
        AND term_type='App\\\Models\\\Prod_routing'
        AND m2m.doc_id=wd.id \n";
        $sql  .= isset($modeParams['prod_routing_id']) && !is_null($modeParams['prod_routing_id']) ? 'AND term_id =' . $modeParams["prod_routing_id"] : "";
        $sqlData = DB::select(DB::raw($sql));
        // dd($sql);
        return $sqlData;
    }



    protected function getDefaultValueModeParams($modeParams, $request)
    {
        // dd($modeParams);
        $x = 'sub_project_id';
        $z = 'prod_routing_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->sub_project_id;
            $modeParams[$z] = $this->prod_routing_id;
        }
        // dd($modeParams);
        return $modeParams;
    }


    protected function transformDataSource($dataSource, $modeParams)
    {
        // dd($dataSource);

        $routeCreate = route("qaqc_wirs.create");
        $items = $dataSource->all();
        $items = Report::pressArrayTypeAllItems($items);

        $entityStatuses = LibStatuses::getFor('qaqc_wir');

        $transformData = array_map(function ($item) use ($entityStatuses) {
            $color = "bg-red-500";
            if (isset($entityStatuses[$item['wir_status']])) {
                $status = $entityStatuses[$item['wir_status']];
                $color = "bg-{$status['color']}-{$status['color_index']}";
            }
            // dd($item);
            $docId = str_pad($item['wir_doc_id'], 4, 0, STR_PAD_LEFT);
            // $hrefEdit = route('qaqc_wirs.edit', $item['wir_id']);
            $hrefEdit = is_null($item['wir_id']) ? "" : route('qaqc_wirs.edit', $item['wir_id']);
            $htmlRender = (object)[
                'value' => $docId,
                'cell_title' => $item['wir_description_name'],
                'cell_href' => $hrefEdit,
                'cell_class' => $color,
            ];

            // modify html sub_project_name + prod_order_name
            $item['sub_project_name'] = (object) [
                'value' => $item['sub_project_name'],
                'cell_title' => $item['sub_project_id'],
            ];
            $item['prod_order_name'] = (object) [
                'value' => $item['prod_order_name'],
                'cell_title' => $item['prod_order_id'],
            ];
            $wirDescName =  is_null($item['wir_description_name']) ? [] : [Report::slugName($item['wir_description_name']) => $htmlRender];
            return (array)$item + $wirDescName;
        }, array_values($items));

        // group by prod_order_id
        $prodGroup = Report::groupArrayByKey($transformData, 'prod_order_id');
        $transformData = Report::mergeArrayValues($prodGroup);

        // set wir_description name for each key
        $dataProdRouting = $this->filterWirDescriptionsFromProdRouting($modeParams);
        $wirDesc = array_column($dataProdRouting, 'wir_description_name', "wir_description_id");
        $keyNameWirDesc = array_merge(...array_map(fn ($item) => [Report::slugName($item) => $item], $wirDesc));
        // dd($keyNameWirDesc);

        // group ['name 'prod_discipline_id] of wir_description for each wir_description's name
        $itemsWirDesc =  DB::table('wir_descriptions')->select('name', 'prod_discipline_id', 'id')->get()->ToArray();
        $itemsWirDescGroup = array_merge(...array_map(fn ($item) => [Report::slugName($item->name) => (array)$item], $itemsWirDesc));
        // dump($itemsWirDescGroup, $keyNameWirDesc);

        $icon = "<i  class='fa-regular fa-circle-plus '></i>";
        foreach ($transformData as $key => $prodOrder) {
            $idx = array_search("wir_status", array_keys($prodOrder));
            $itemHasWirDesc = array_slice($prodOrder, $idx + 1, count($prodOrder) - $idx, true);
            $itemHasNotWirDesc = array_diff_key($keyNameWirDesc, $itemHasWirDesc);
            // dd($prodOrder);


            $param1 = '/?project_id=' . $prodOrder['project_id'];
            $param2 = 'sub_project_id=' . $prodOrder['sub_project_id'];
            $param3 = 'prod_routing_id=' . $prodOrder['prod_routing_id'];
            $param4 = 'prod_order_id=' . $prodOrder['prod_order_id'];
            $itemsHasNotWirDescData = [];
            foreach (array_keys($itemHasNotWirDesc) as $keyName) {
                $param5 = 'prod_discipline_id=' . $itemsWirDescGroup[$keyName]['prod_discipline_id'];
                $param6 = 'wir_description_id=' . $itemsWirDescGroup[$keyName]['id'];
                $param7 = $itemsWirDescGroup[$keyName]['name'];
                $params = [$param1, $param2, $param3, $param4, $param5, $param6];
                $href = $routeCreate . implode('&', $params);
                $itemsHasNotWirDescData[$keyName] = (object)[
                    'value' => $icon,
                    'cell_title' => $param7,
                    'cell_href' => $href,
                    'cell_class' => 'bg-green-50',
                ];
            }
            $transformData[$key] =  $prodOrder + $itemsHasNotWirDescData;
        }
        // dd($transformData);
        return collect($transformData);
    }
}
