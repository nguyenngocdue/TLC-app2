<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Sub_project;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Qaqc_wir extends Report_ParentController
{
    use TraitReport;
    public function getSqlStr($modeParams)
    {
        $sql =
            "SELECT tb2.*
                        ,wir.id AS wir_id
                        ,wir.doc_id AS doc_id
                        ,wir.status AS wir_status
                        FROM(SELECT 
                        tb1.sub_project_name
                        ,tb1.sub_projects_id
                        ,tb1.prod_name 
                        ,tb1.prod_id 
                        ,wirdesc.id AS wirdesc_id
                        ,wirdesc.name AS wirdesc_name
                        FROM (SELECT
                        sub.name AS sub_project_name
                        ,prod.sub_project_id AS sub_projects_id
                        ,prod.name AS prod_name
                        ,prod.id AS prod_id
                        ,prodr.id AS prod_routings_id
                        FROM prod_orders prod, sub_projects sub, prod_routings prodr
                        WHERE 1 = 1";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND prod.sub_project_id = '{{sub_project_id}}'";
        if (isset($modeParams['prod_routing_id'])) $sql .= "\n AND prodr.id = '{{prod_routing_id}}'";
        $sql .= "\n AND sub.id = prod.sub_project_id
                        AND prodr.id = prod.prod_routing_id ) AS tb1
                        LEFT JOIN prod_routing_details prodrd ON prodrd.prod_routing_id = tb1.prod_routings_id
                        LEFT JOIN wir_descriptions wirdesc ON prodrd.wir_description_id = wirdesc.id
                        GROUP BY wirdesc_id, prod_id ) AS tb2
                        LEFT JOIN qaqc_wirs wir ON wir.prod_order_id = tb2.prod_id 
                        AND wir.wir_description_id = tb2.wirdesc_id
                        ORDER BY prod_id ASC";

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

        // dd($items);
        $flattenData = array_merge(...$items);
        $idx = array_search("wir_status", array_keys($flattenData));
        $dataColumn = array_slice($flattenData, $idx + 1, count($flattenData) - $idx, true);
        ksort($dataColumn);

        $adds = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name_html",
                "align" => "center",
            ],
            [
                "title" => "Modular Name",
                "dataIndex" => "prod_name_html",
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
                prodr.id AS prod_routings_id
                ,prodr.name AS prod_routings_name
                FROM prod_orders prod, sub_projects sub, prod_routings prodr
                WHERE 1 = 1
                AND sub.id = prod.sub_project_id
                AND prodr.id = prod.prod_routing_id
                GROUP BY prod_routings_id,  prod_routings_name";
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }

    protected function getDataForModeControl($dataSource = [])
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        $dataProdRouting = $this->getDataProdRouting();
        $prodRoutings = ['prod_routing_id' => array_column($dataProdRouting, 'prod_routings_name', 'prod_routings_id')];
        return array_merge($subProjects, $prodRoutings);
    }








    protected function transformDataSource($dataSource, $modeParams)
    {

        $icon = "<i  class='fa-regular fa-circle-plus '></i>";
        $routeCreate = route("qaqc_wirs.create");
        $items = $dataSource->all();
        $entityStatuses = LibStatuses::getFor('qaqc_wir');

        $transformData = array_map(function ($item) use ($entityStatuses, $routeCreate, $icon) {
            $color = "";
            if (!is_null($item->wir_status) && isset($entityStatuses[$item->wir_status])) {
                $status = $entityStatuses[$item->wir_status];
                $color = "bg-{$status['color']}-{$status['color_index']}";
            }
            if (!is_null($item->wir_status) && !isset($entityStatuses[$item->wir_status])) {
                $color = 'bg-red-500';
            }
            $route = !is_null($item->doc_id) ? route('qaqc_wirs.show', $item->wir_id) : $routeCreate;
            $docId = str_pad($item->doc_id, 4, 0, STR_PAD_LEFT);
            $html = "<div style='width: 36px' class='$color' title=''><a href='$route'>$docId</a></div>";
            if (is_null($item->doc_id)) {
                $html =  "<div  style='width: 36px' class='bg-white' title='prod_id: {$item->prod_id}'><a href='$route'>$icon</a></div>";
            }

            $wirdescName = [];
            if (!is_null($item->wirdesc_name)) {
                $wirdescName = [Report::slugName($item->wirdesc_name) => $html];
            }

            $prodNameHtml = ['prod_name_html' =>  "<div  style='width: 80px'>$item->prod_name</div>"];
            $subProjectNameHtml = ['sub_project_name_html' =>  "<div  style='width: 80px'>$item->sub_project_name</div>"];
            return (array)$item + $wirdescName + $prodNameHtml + $subProjectNameHtml;
        }, array_values($items));

        $prodGroup = Report::groupArrayByKey($transformData, 'prod_id');
        $result = Report::mergeArrayValues($prodGroup);

        $wirDesc = $this->getDataWirDescription();
        $wirDesc = array_column($wirDesc, 'wir_description_name');
        $wirDesc = array_map(fn ($item) => Report::slugName($item), $wirDesc);

        $transformData = array_map(function ($item) use ($wirDesc, $routeCreate, $icon) {
            $arrayDiff = array_diff($wirDesc, array_keys($item));
            $html =  "<div style='width: 36px' class='bg-white ' title='prod_id: {$item['prod_id']}'><a href='$routeCreate'>$icon</a></div>";
            $combineArray = array_merge(...array_map(fn ($item) => [$item => $html], $arrayDiff));
            $transformData = $item + $combineArray;
            return $transformData;
        }, $result);


        return collect($transformData);
    }
}
