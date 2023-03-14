<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Sub_project;
use App\Models\Wir_description;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Qaqc_wir_010 extends Report_ParentController
{
    use TraitReport;
    protected $rotate45Width = 600;
    public function getSqlStr($modeParams)
    {
        $sql = "SELECT 
        pairs.sub_project_id
        ,pairs.project_id
        ,sub_project_name
        ,pairs.prod_order_id
        ,pairs.prod_order_name
        ,wir_description_id
        ,wir_description_name
        ,pairs.prod_routing_id
        ,pairs.prod_routing_name
        ,qw.id AS wir_id
        ,qw.doc_id AS wir_doc_id
        ,qw.prod_discipline_id AS wir_prod_discipline_id
        ,qw.status AS wir_status
        FROM qaqc_wirs AS qw,
        (SELECT * 
            FROM(SELECT 
                 sp.id AS sub_project_id
                ,sp.project_id AS project_id
                ,sp.name AS sub_project_name
                ,po.id AS prod_order_id
                ,po.name AS prod_order_name 
                ,pr.id AS prod_routing_id
                ,pr.name AS prod_routing_name
                FROM  sub_projects sp, prod_orders po, prod_routings pr
                    WHERE 1 = 1";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id = '{{sub_project_id}}'";
        if (isset($modeParams['prod_routing_id'])) $sql .= "\n AND pr.id = '{{prod_routing_id}}'";
        $sql .= "\n AND sp.id = po.sub_project_id	
                    AND po.prod_routing_id = pr.id) wirPo,
                (SELECT wd.id as wd_id, wd.name AS wir_description_name
                FROM many_to_many m2m, wir_descriptions wd
                WHERE 1 =1 
                AND doc_type='App\\\Models\\\Wir_description'
                AND term_type='App\\\Models\\\Prod_routing'";
        if (isset($modeParams['prod_routing_id'])) $sql .= "\n AND term_id = '{{prod_routing_id}}'";
        $sql .= " \n AND m2m.doc_id=wd.id) AS wirDesc ) pairs
                    WHERE 1=1
                    AND qw.prod_order_id=pairs.prod_order_id
                    AND qw.wir_description_id=pairs.wd_id
          ";

        return $sql;
    }

    private function getDataWirDescription()
    {
        $sql = "SELECT wirdesc.id AS wir_description_id, wirdesc.name wir_description_name
                        FROM wir_descriptions wirdesc;";
        return $this->getDataSourceFromSqlStr($sql);
    }

    protected function getTableColumns($dataSource)
    {
        $items = $dataSource->items();
        // dd($dataSource);
        if (!is_array($dataSource->items())) {
            $items = Report::pressArrayTypeAllItems($dataSource);
        }
        $flattenData = array_merge(...$items);
        $idx = array_search("wir_status", array_keys($flattenData));
        $dataColumn = array_slice($flattenData, $idx + 1, count($flattenData) - $idx, true);
        unset($dataColumn['wir_status']);
        ksort($dataColumn);

        $adds = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name_html",
                "align" => "center",
            ],
            [
                "title" => "Production Order Name",
                "dataIndex" => "prod_order_name_html",
                "align" => "center",
            ],
            [
                "title" => "Production Order ID",
                "dataIndex" => "prod_order_id",
                "align" => "center",
            ]
        ];
        // dd($dataColumn);
        $sqlCol =  array_map(fn ($item) => ["dataIndex" => $item, "align" => "center", "width" => 100], array_keys($dataColumn));
        $dataColumn = array_merge($adds, $sqlCol);
        // dd($sqlCol);


        return  $dataColumn;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Sub Porject',
                'dataIndex' => 'sub_project_id',
                // 'allowClear' => true
            ],
            [
                'title' => 'Prod Routing',
                'dataIndex' => 'prod_routing_id',
                // 'allowClear' => true
            ]
        ];
    }

    protected function filterWirDescriptionsFromProdRouting($modeParams)
    {

        // dd($modeParams);
        $sql = "SELECT wd.id as wir_description_id, wd.name AS wir_description_name
        FROM many_to_many m2m, wir_descriptions wd
        WHERE 1 =1 
        AND doc_type='App\\\Models\\\Wir_description'
        AND term_type='App\\\Models\\\Prod_routing'
        AND m2m.doc_id=wd.id
        AND term_id = ";
        $sql .= $modeParams['prod_routing_id'];
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }


    protected function getDataProdRouting()
    {
        $sql = "
            SELECT
                prodr.id AS prod_routing_id
                ,prodr.name AS prod_routing_name
                FROM prod_orders prod, sub_projects sub, prod_routings prodr
                WHERE 1 = 1
                AND sub.id = prod.sub_project_id
                AND prodr.id = prod.prod_routing_id
                GROUP BY prod_routing_id,  prod_routing_name";
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }
    protected function getDataForModeControl($dataSource = [])
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        $dataProdRouting = $this->getDataProdRouting();
        $prodRoutings = ['prod_routing_id' => array_column($dataProdRouting, 'prod_routing_name', 'prod_routing_id')];
        return array_merge($subProjects, $prodRoutings);
    }


    protected function setDefaultValueModeParams($modeParams)
    {

        $x = 'sub_project_id';
        $y = 'prod_routing_id';
        if (!isset($modeParams[$x]) || empty($modeParams)) {
            return [$x => 82, $y => 6];
        }
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
            $docId = str_pad($item['wir_doc_id'], 4, 0, STR_PAD_LEFT);
            $hrefEdit = route('qaqc_wirs.edit', $item['wir_id']);

            $htmlRender = (object)[
                'value' => $docId,
                'cell_title' => $item['wir_description_name'],
                'cell_href' => $hrefEdit,
                'cell_class' => $color,
            ];
            $wirDescName = [Report::slugName($item['wir_description_name']) => $htmlRender];

            // Edit visibility of production_order_name + sub_project_name to display in the table
            $prodNameHtml = ['prod_order_name_html' =>  "<div  style='width: 120px'>{$item['prod_order_name']}</div>"];
            $subProjectNameHtml = ['sub_project_name_html' =>  "<div  style='width: 80px'>{$item['sub_project_name']}</div>"];

            return $prodNameHtml + $subProjectNameHtml + (array)$item + $wirDescName;
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
        // dd($itemsWirDescGroup);

        $icon = "<i  class='fa-regular fa-circle-plus '></i>";
        foreach ($transformData as $key => $prodOrder) {
            $idx = array_search("wir_status", array_keys($prodOrder));
            $itemHasWirDesc = array_slice($prodOrder, $idx + 1, count($prodOrder) - $idx, true);
            $itemHasNotWirDesc = array_diff_key($keyNameWirDesc, $itemHasWirDesc);

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
        return collect($transformData);
    }
}
