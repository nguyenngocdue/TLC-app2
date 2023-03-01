<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Sub_project;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Report;

class Qaqc_wir extends Report_ParentController
{
    use TraitReport;
    protected $pagingSize = 1000;
    public function getSqlStr($urlParams)
    {
        // dd($urlParams);
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
        if (isset($urlParams['sub_project_id'])) $sql .= "\n AND prod.sub_project_id = '{{sub_project_id}}'";

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

    public function getTableColumns($dataSource)
    {
        $items = $dataSource->items();
        if (!is_array($dataSource->items()[0])) {
            $items = Report::pressArrayTypeAllItems($dataSource);
        }

        // dd($items);
        $flattenData = array_merge(...$items);
        $idx = array_search("wir_status", array_keys($flattenData));
        $dataColumn = array_slice($flattenData, $idx + 1, count($flattenData) - $idx, true);

        $adds = [
            [
                "dataIndex" => "sub_project_name",
                "align" => ""
            ],
            [
                "title" => "Modular Name",
                "dataIndex" => "prod_name",
                "align" => ""
            ],
            [
                "title" => "Prod ID",
                "dataIndex" => "prod_id",
                "align" => "right"
            ]
        ];
        // dd($dataColumn);
        $sqlCol =  array_map(fn ($item) => ["dataIndex" => $item, "align" => "center"], array_keys($dataColumn));
        $dataColumn = array_merge($adds, $sqlCol);
        // dd($sqlCol);


        return  $dataColumn;
    }

    public function getDataForModeControl($dataSource = [])
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        return array_merge($subProjects);
    }


    protected function enrichDataSource($dataSource, $urlParams)
    {

        $routeCreate = route("qaqc_wirs.create");
        $items = $dataSource->items();
        $entityStatuses = LibStatuses::getFor('qaqc_wir');
        $enrichData = array_map(function ($item) use ($entityStatuses, $routeCreate) {
            $color = "";
            if (!is_null($item->wir_status) && isset($entityStatuses[$item->wir_status])) {
                $status = $entityStatuses[$item->wir_status];
                $color = "bg-{$status['color']}-{$status['color_index']}";
            }
            if (!is_null($item->wir_status) && !isset($entityStatuses[$item->wir_status])) {
                $color = 'bg-red-500';
            }
            $route = !is_null($item->doc_id) ? route('qaqc_wirs.show', $item->wir_id) : $routeCreate;
            $html = "<div class='$color' title=''><a href='$route'>$item->doc_id</a></div>";
            if (is_null($item->doc_id)) {
                $html =  "<div class='bg-gray-400' title='prod_id: {$item->prod_id}'><a href='$route'><i class='fa-regular fa-circle-plus'></i></a></div>";
            }

            $arr = [];
            if (!is_null($item->wirdesc_name)) {
                $arr = [Report::slugName($item->wirdesc_name) => $html];
            }
            return (array)$item + $arr;
        }, array_values($items));
        // dd($enrichData);

        $prodGroup = Report::groupArrayByKey($enrichData, 'prod_id');
        $result = Report::mergeArrayValues($prodGroup);
        // dd($enrichData);

        $wirDesc = $this->getDataWirDescription();
        $wirDesc = array_column($wirDesc, 'wir_description_name');
        $wirDesc = array_map(fn ($item) => Report::slugName($item), $wirDesc);
        // dd($wirDesc);

        $enrichData = array_map(function ($item) use ($wirDesc, $routeCreate) {
            $arrayDiff = array_diff($wirDesc, array_keys($item));
            // dd($arrayDiff, $wirDesc, $item);

            $html =  "<div class='bg-gray-400' title='prod_id: {$item['prod_id']}'><a href='$routeCreate'><i class='fa-regular fa-circle-plus'></i></a></div>";
            $combineArray = array_merge(...array_map(fn ($item) => [$item => $html], $arrayDiff));
            // dd($combineArray);
            $enrichData = $item + $combineArray;
            return $enrichData;
        }, $result);
        // dd($enrichData);

        $dataSource->setCollection(collect($enrichData));
        // dd($dataSource);
        return $dataSource;
    }
}
