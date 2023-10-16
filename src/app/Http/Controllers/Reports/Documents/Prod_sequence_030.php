<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Http\Controllers\Reports\TraitUpdateBasicInfoDataSource;
use App\Models\Prod_discipline;
use App\Models\Prod_routing;
use App\Models\Prod_routing_link;
use App\Models\Project;
use App\Models\Sub_project;
use App\Utils\Support\DateReport;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Prod_sequence_030 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitUpdateBasicInfoDataSource;
    use TraitGenerateValuesFromParamsReport;

    protected $mode = '030';
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 49;
    protected $groupByLength = 1;
    protected $viewName = 'document-prod-sequence-030';
    protected $type = 'prod_sequence';
    protected $tableTrueWidth = true;
    protected $overTableTrueWidth = false;
    protected $pageLimit = 100000;
    protected $optionPrint = "landscape";

    public function getSqlStr($params)
    {

        $valOfParams = $this->generateValuesFromParamsReport($params);
        // dd($valOfParams);
        $sql = "SELECT 
                    tb1.*
                    ,count_po_finished
                    ,count_original_po
                    ,FORMAT(count_po_finished*100/tb2.count_original_po,2) AS finished_progress
                    ,prd.order_no AS order_no
                    FROM (SELECT
                    pj.name AS project_name,
                    sp.project_id AS project_id,
                    sp.id AS sub_project_id,
                    sp.name AS sub_project_name,
                    GROUP_CONCAT(po.id, ',')  AS prod_order_id,
                    pr.id AS prod_routing_id,
                    pr.name AS prod_routing_name,
                    ps.status AS prod_sequence_status,
                    prl.id AS prod_routing_link_id,
                    prl.name prod_routing_link_name,
                    prl.prod_discipline_id AS prod_discipline_id,
                    pdis.name AS prod_discipline_name,
                    COUNT(DISTINCT po.id) AS count_po_finished
                    FROM 
                        sub_projects sp, 
                        prod_orders po, 
                        prod_routings pr,
                        prod_sequences ps,
                        prod_routing_links prl,
                        prod_disciplines pdis,
                        projects AS pj
                    WHERE 1 = 1
                    AND pj.id = sp.project_id";
        if (isset($valOfParams['sub_project_id'])) $sql .= "\n AND sp.id = {{sub_project_id}}";
        if (isset($valOfParams['project_id'])) $sql .= "\n AND pj.id = {{project_id}}";
        if (isset($valOfParams['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id = {{prod_routing_id}}";
        if (Report::checkValueOfField($valOfParams, 'prod_discipline_id'))  $sql .= "\n AND prl.prod_discipline_id IN ({{prod_discipline_id}})";
        // if (isset($valOfParams['prod_routing_link_id'])) $sql .= "\n AND prl.id IN({{prod_routing_link_id}}) ";

        $sql .= "\n AND ps.status = 'finished'
                    AND po.sub_project_id = sp.id
                    AND po.prod_routing_id = pr.id
                    AND ps.prod_routing_link_id = prl.id
                    AND ps.prod_order_id = po.id
                    AND prl.id = ps.prod_routing_link_id
                    AND pdis.id = prl.prod_discipline_id
                    GROUP BY prod_routing_link_id, prod_routing_id ) AS tb1
                    LEFT JOIN prod_routing_details prd ON prd.prod_routing_id = tb1.prod_routing_id AND prd.prod_routing_link_id = tb1.prod_routing_link_id
                    LEFT JOIN (
                            SELECT
                                sp.id AS sub_project_id,
                                po.prod_routing_id AS prod_routing_id, 
                                COUNT(po.id)  AS count_original_po
                                FROM 
                                    sub_projects sp, 
                                    prod_orders po
                                WHERE 1 = 1
                                AND po.sub_project_id = sp.id";
        if (isset($valOfParams['sub_project_id'])) $sql .= "\n AND sp.id = {{sub_project_id}}";
        if (isset($valOfParams['project_id'])) $sql .= "\n AND sp.project_id = {{project_id}}";
        $sql .= "\n  AND po.deleted_at IS NULL
                            GROUP BY prod_routing_id) AS tb2 ON tb1.sub_project_id = tb2.sub_project_id
                            AND tb1.prod_routing_id = tb2.prod_routing_id";
        return $sql;
    }


    protected function getDefaultValueParams($params, $request)
    {
        $params['project_id'] = $this->projectId;
        $params['sub_project_id'] = $this->subProjectId;
        $params['prod_routing_id'] = $this->prodRoutingId;
        return $params;
    }

    private function getProdRoutingLinks($params){
        $valOfParams = $this->generateValuesFromParamsReport($params);
        // dd($valOfParams);
        $sql = "SELECT 
                        prl.id AS prod_routing_link_id,
                        prl.name AS prod_routing_link_name,
                        pdisc.id AS prod_discipline_id,
                        pdisc.name AS prod_discipline_name,
                        pdisc.description AS prod_discipline_description,
                        pr.id AS prod_routing_id,
                        pr.name AS prod_routing_name,
                        pr.description AS prod_routing_description

                        FROM    sub_projects sp, prod_orders po, 
                                prod_routing_details prd, 
                                prod_routing_links prl, 
                                prod_routings pr, prod_disciplines pdisc
                        WHERE 1 = 1";
                        if (isset($valOfParams['sub_project_id'])) $sql .= "\n AND sp.id = {$valOfParams['sub_project_id']}";
                        if (isset($valOfParams['project_id'])) $sql .= "\n AND sp.project_id = {$valOfParams['project_id']}";
                        if (isset($valOfParams['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id = {$valOfParams['prod_routing_id']}";
                        if (Report::checkValueOfField($valOfParams, 'prod_discipline_id'))  $sql .= "\n AND prl.prod_discipline_id IN ({$valOfParams['prod_discipline_id']})";
                        $sql .="\n 
                                    AND pr.id = prd.prod_routing_id
                                    AND prl.id = prd.prod_routing_link_id
                                    AND po.prod_routing_id = pr.id
                                    AND sp.id = po.sub_project_id
                                    AND pdisc.id = prl.prod_discipline_id
                                    GROUP BY prod_routing_link_id
                                    ";
                                    // dd($sql);
        $sqlData = DB::select(DB::raw($sql));
        $collection = collect($sqlData);
        return $collection;
    }

    private function updateDataForPivotChart($dataSource, $params)
    {
        $items = array_values($dataSource->toArray());
        // dd($dataSource);
        $groupItems = Report::groupArrayByKey($items,'prod_discipline_id');
        $collectionItems =  array_merge(...$groupItems);
        uasort($collectionItems, function($a, $b){
            return $a['order_no'] - $b['order_no'];
        });
        $collectionItems = [implode('_',array_keys($groupItems)) => $collectionItems];

        foreach($collectionItems as $key => $values){
            // dd($values);
            $firstItem = reset($values);
            $infoRoutingLinks = array_column($values, 'finished_progress', 'prod_routing_link_name');
            // information for meta data
            $labels = StringReport::arrayToJsonWithSingleQuotes(array_keys($infoRoutingLinks));
            $numbers = StringReport::arrayToJsonWithSingleQuotes(array_values($infoRoutingLinks));
            $max = count(array_keys($infoRoutingLinks));
            $count = count($infoRoutingLinks);
            $meta = [
                'labels' => $labels,
                'numbers' => $numbers,
                'max' => $max,
                'count' => $count
            ];
            
            // information for metric data
            $metric = [];
            array_walk($infoRoutingLinks, function ($value, $key) use (&$metric) {
                return $metric[] = (object) [
                    'meter_id' => $key,
                    'metric_name' => $value
                ];
            });
            // relate to dimensions AxisX and AxisY
            $dimensions = [
                'scaleMaxX' => 100,
                'fontSize' => 14,
                'titleX' => "% Complete",
                'indexAxis' => 'y',
                'width' => 400,
                'height' => $max/2*30,
                'dataLabelAlign' => 'end',
                'dataLabelOffset' => 10,
                'displayTitleOnTopCol' => 1,
            ];
    
            // Set data for widget
            $widgetData =  [
                "title_a" => "Production Routing Link".$key,
                "title_b" => "by progress",
                'meta' => $meta,
                'metric' => $metric,
                'chartType' => 'bar',
                'titleChart' => '',
                'dimensions' => $dimensions,
                // 'basicInfo' => [
                //     "project_name" => $firstItem['project_name'],
                //     "sub_project_name" => $firstItem['sub_project_name'],
                //     "prod_routing_name" => $firstItem['prod_routing_name'],
                //     "prod_discipline_name" => $firstItem['prod_discipline_name']
                // ],
            ];
            $data['widget_'. $key] = $widgetData;
            // dd($data);
            
        };

        // add widget to dataSource
        $data['tableDataSource'] =  $dataSource;
        // dd($data);
        return collect($data);
    }


    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Project',
                'dataIndex' => 'project_id',
            ],
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
                'hasListenTo' => true,
            ],
            [
                'title' => 'Production Routing',
                'dataIndex' => 'prod_routing_id',
                'hasListenTo' => true,
                'validation' => 'required',
            ],
            [
                'title' => 'Production Discipline',
                'dataIndex' => 'prod_discipline_id',
                'allowClear' => true,
                //'hasListenTo' => true,
                'multiple' => true,
            ],
        ];
    }

    public function getTableColumns($params, $dataSource)
    {
        return
            [
                [
                    "title" => "Project",
                    "dataIndex" => "project_name",
                    "align" => "left",
                    "width" => 110,
                ],
                [
                    "title" => "Sub Project",
                    "dataIndex" => "sub_project_name",
                    "align" => "left",
                    "width" => 110,
                ],
                [
                    "title" => "Production Routing",
                    "dataIndex" => "prod_routing_name",
                    "align" => "left",
                    "width" => 200,
                ],
                [
                    "title" => "Production Discipline",
                    "dataIndex" => "prod_discipline_name",
                    "align" => "left",
                    "width" => 152,
                ],
                [
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
                    "align" => "left",
                    "width" => 300,
                ],
                [
                    "title" => "Total Production Orders",
                    "dataIndex" => "count_original_po",
                    "align" => "right",
                    "width" => 137,
                ],
                [
                    "title" => "Count Production Orders <br/>(status = finished)",
                    "dataIndex" => "count_po_finished",
                    "align" => "right",
                    "width" => 137,
                ],
                [
                    "title" => "% Complete",
                    "dataIndex" => "finished_progress",
                    "align" => "right",
                    "width" => 137,
                ],
            ];
    }

    public function getBasicInfoData($params)
    {
        $projectName = Project::find($params['project_id'] ?? $this->projectId)->name;
        $subProjectName = Sub_project::find($params['sub_project_id'] ?? $this->subProjectId);
        $prodPouting = Prod_routing::find($params['prod_routing_id'] ?? $this->prodRoutingId)->name;

        $prodDiscipline = isset($params['prod_discipline_id']) ?
        implode(', ', Prod_discipline::find($params['prod_discipline_id'])
            ->pluck('name')
            ->toArray()) :
        implode(', ', Prod_discipline::all()
            ->pluck('name')
            ->toArray());

        $prodRoutingLink = isset($params['prod_routing_link_id']) ?
            implode(',', Prod_routing_link::find($params['prod_routing_link_id'])
                ->pluck('name')
                ->toArray()) : '';

        $basicInfoData = [];
        $basicInfoData['project_name'] = $projectName;
        $basicInfoData['sub_project_name'] = $subProjectName->name;
        $basicInfoData['prod_routing_name'] = $prodPouting;
        $basicInfoData['prod_routing_link_name'] = $prodRoutingLink;
        $basicInfoData['prod_discipline_name'] = $prodDiscipline;
        // dd($basicInfoData);
        return $basicInfoData;
    }



    public function changeDataSource($dataSource, $params)
    {
        // dump($dataSource);
        $data = $dataSource instanceof Collection ? $dataSource->toArray() : $dataSource;
        $prodRoutingLinkFinished = array_column($data, 'prod_routing_link_name', 'prod_routing_link_id');

        $prodRoutingLinks = $this->getProdRoutingLinks($params);
        $firstItem = reset($data);
        foreach ($prodRoutingLinks as $key => $values){
            if(!isset($prodRoutingLinkFinished[$values->prod_routing_link_id])){
                // dd($values);
                $values = (array)$values;
                $firstItem = (array)$firstItem;
                $firstItem['finished_progress'] = null;
                $mergedData = array_replace_recursive($firstItem,$values);
                $data[] = (object)$mergedData;
            }
        }
        $data = collect($data)->sortBy([
            // ['project_name'],
            // ['sub_project_name'],
            // ['prod_routing_name'],
            // ['prod_discipline_name'],
            // ['prod_routing_link_name'],
            ['order_no'],
        ]);
        // dd($data);
        $dataSource = self::updateDataForPivotChart($data, $params);
        return $dataSource;
    }
}
