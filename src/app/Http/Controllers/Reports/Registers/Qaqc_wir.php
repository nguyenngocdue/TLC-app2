<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Sub_project;
use App\Models\Wir_description;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Qaqc_wir extends Report_ParentController
{
    use TraitReport;
    public function getSqlStr($modeParams)
    {
        $sql = "SELECT tb1.*
        , wir.prod_order_id AS wir_prod_order_id
        , wir.sub_project_id AS wir_sub_project_id
        , wir.wir_description_id AS wir_description_id
        , wirdesc.name AS wir_description_name
        ,wir.doc_id AS wir_doc_id
        ,wir.prod_discipline_id AS wir_prod_discipline_id
        ,wir.status AS wir_status
        FROM( SELECT
            sub.name AS sub_project_name
            ,prod.sub_project_id AS sub_project_id
            ,prod.name AS prod_order_name
            ,prod.id AS prod_order_id
            ,prodr.id AS prod_routing_id
            ,prodr.name AS prod_routing_name
            ,sub.project_id AS project_id 
            FROM prod_orders prod, sub_projects sub, prod_routings prodr
            WHERE 1 = 1";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND prod.sub_project_id = '{{sub_project_id}}'";
        if (isset($modeParams['prod_routing_id'])) $sql .= "\n AND prodr.id = '{{prod_routing_id}}'";
        $sql .= "\n AND sub.id = prod.sub_project_id
                 #AND prod.id = 372
                 AND prodr.id = prod.prod_routing_id) tb1
                 LEFT JOIN qaqc_wirs wir ON wir.prod_order_id = tb1.prod_order_id
                     AND wir.sub_project_id = tb1.sub_project_id
                 LEFT JOIN wir_descriptions wirdesc ON wir.wir_description_id = wirdesc.id
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
        if (!is_array($dataSource->items())) {
            $items = Report::pressArrayTypeAllItems($dataSource);
        }
        $flattenData = array_merge(...$items);
        $idx = array_search("wir_status", array_keys($flattenData));
        $dataColumn = array_slice($flattenData, $idx + 1, count($flattenData) - $idx, true);
        unset($dataColumn['wir_status']);
        // dd($dataColumn);
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


    protected function filterProdRoutingFollowWirDescription()
    {
        $sql = "SELECT #tb1.*
                    prodr.id AS prod_routing_id, 
                    prodr.name AS prod_routing_name
                    ,wirdesc.id AS wir_description_id,wirdesc.name AS wir_description_name
                FROM (SELECT
                        mtm.doc_type AS doc_type,
                        mtm.doc_id AS doc_id,
                        mtm.term_type AS term_type,
                        mtm.term_id AS term_id
                        FROM many_to_many mtm
                            WHERE 1 = 1
                            AND mtm.term_type LIKE '%Prod_routing%'
                            AND mtm.doc_type LIKE '%Wir_description%') tb1
                            JOIN prod_routings prodr ON tb1.term_id = prodr.id
                            JOIN wir_descriptions wirdesc ON wirdesc.id = tb1.doc_id";
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

    protected function transformDataSource($dataSource, $modeParams)
    {

        $icon = "<i  class='fa-regular fa-circle-plus '></i>";
        $routeCreate = route("qaqc_wirs.create");

        $items = $dataSource->all();
        $items = Report::pressArrayTypeAllItems($items);

        $entityStatuses = LibStatuses::getFor('qaqc_wir');

        $transformData = array_map(function ($item) use ($entityStatuses, $routeCreate, $icon) {
            $color = "";
            if (!is_null($item['wir_status']) && isset($entityStatuses[$item['wir_status']])) {
                $status = $entityStatuses[$item['wir_status']];
                $color = "bg-{$status['color']}-{$status['color_index']}";
            }
            if (!is_null($item['wir_status']) && !isset($entityStatuses[$item['wir_status']])) {
                $color = 'bg-red-500';
            }
            // set url when Onclick icon
            $params = $this->getParamForUrl($item);
            $href = $routeCreate . implode('&', $params);
            $docId = str_pad($item['wir_doc_id'], 4, 0, STR_PAD_LEFT);
            $html = "<div class='$color w-full' title='{$item['wir_description_name']}'><a href='$href'>$docId</a></div>";
            if (is_null($item['wir_doc_id'])) {
                $html =  "<div class='w-full ' title='{$item['wir_description_name']}/{$item['wir_description_id']}'><a href='$href'>$icon</a></div>";
            }

            $wirdescName = [];
            if (!is_null($item['wir_description_name'])) {
                $wirdescName = [Report::slugName($item['wir_description_name']) => $html];
            }

            $prodNameHtml = ['prod_order_name_html' =>  "<div  style='width: 120px'>{$item['prod_order_name']}</div>"];
            $subProjectNameHtml = ['sub_project_name_html' =>  "<div  style='width: 80px'>{$item['sub_project_name']}</div>"];
            return $prodNameHtml + $subProjectNameHtml + (array)$item + $wirdescName;
        }, array_values($items));

        // dd($transformData);


        // group by prod_order_id
        $prodGroup = Report::groupArrayByKey($transformData, 'prod_order_id');

        $transformData = Report::mergeArrayValues($prodGroup);
        // dd($transformData);


        // Add fields are null
        $dataProdRouting = $this->filterProdRoutingFollowWirDescription();
        $prodRoutingGroup = Report::groupArrayByKey($dataProdRouting, 'prod_routing_id');
        $groupByIdProdRouting = [];
        foreach ($prodRoutingGroup as $key => $value) {
            $groupByIdProdRouting[$key] = array_column($value, 'wir_description_name', "wir_description_id");
        }
        // * get parameters from  "setting field" in database
        $settings = CurrentUser::getSettings();
        $lstRenderColWirDesc = [];
        if (isset($settings['qaqc_wirs'])) {
            $idProdRouting = $settings['qaqc_wirs']['registers']['mode_001']['prod_routing_id'] ?? 0;
            $lstRenderColWirDesc = $groupByIdProdRouting[$idProdRouting];
            $lstRenderColWirDesc = array_map(fn ($item) => Report::slugName($item), $lstRenderColWirDesc);
        }

        $itemsWirDesc =  DB::table('wir_descriptions')->select('name', 'prod_discipline_id', 'id')->get()->ToArray();
        $itemsWirDesc = Report::pressArrayTypeAllItems($itemsWirDesc);
        $_itemsWirDesc = [];
        foreach ($itemsWirDesc as $key => $value) {
            $_itemsWirDesc[Report::slugName($value['name'])] = $value;
        }

        foreach ($transformData as $key => $prodOrder) {
            $arrayDiff = array_diff($lstRenderColWirDesc, array_keys($prodOrder));
            $param1 = '/?project_id=' . $prodOrder['project_id'];
            $param2 = 'sub_project_id=' . $prodOrder['sub_project_id'];
            $param3 = 'prod_routing_id=' . $prodOrder['prod_routing_id'];
            $param4 = 'prod_order_id=' . $prodOrder['prod_order_id'];
            $lackFieldArray = [];
            foreach ($arrayDiff as $id => $name) {
                $param5 = 'prod_discipline_id=' . $_itemsWirDesc[$name]['prod_discipline_id'];
                $param6 = 'wir_description_id=' . $_itemsWirDesc[$name]['id'];
                $param7 = $_itemsWirDesc[$name]['name'];
                $params = [$param1, $param2, $param3, $param4, $param5, $param6];
                $href = $routeCreate . implode('&', $params);
                $html =  "<div  class=' w-full'  title='{$param7}' ><a href={$href}>$icon</a></div>";
                $lackFieldArray[$name] = "$html";
            }
            $transformData[$key] = $prodOrder + $lackFieldArray;
        }
        return collect($transformData);
    }



    protected  function getParamForUrl($item)
    {
        $param1 = '/?project_id=' . $item['project_id'];
        $param2 = 'sub_project_id=' . $item['sub_project_id'];
        $param3 = 'prod_routing_id=' . $item['prod_routing_id'];
        $param4 = 'prod_order_id=' . $item['prod_order_id'];
        $param5 = 'wir_prod_discipline_id=' . $item['wir_prod_discipline_id'];
        $param6 = 'wir_description_id=' . $item['wir_description_id'];
        return [$param1, $param2, $param3, $param4, $param5, $param6];
    }
}
