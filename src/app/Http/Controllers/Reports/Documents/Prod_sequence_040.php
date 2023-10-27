<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Http\Controllers\Reports\TraitUpdateBasicInfoDataSource;
use App\Models\Prod_discipline;
use App\Models\Prod_routing;
use App\Models\Prod_routing_link;
use App\Models\Project;
use App\Models\Sub_project;
use App\Utils\Support\Report;
use App\View\Components\Renderer\Report\TraitCreateDataSourceWidget;
use App\View\Components\Renderer\Report\TraitParamsInManageWidget;

class Prod_sequence_040 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitUpdateBasicInfoDataSource;
    use TraitParamsInManageWidget;
    use TraitCreateDataSourceWidget;

    protected $mode = '020';
    protected $subProjectId = [82, 21];
    protected $prodRoutingId = 6;
    protected $groupByLength = 1;

    protected $viewName = 'document-prod-sequence-040';
    protected $type = 'prod_sequence';
    protected $tableTrueWidth = true;
    protected $overTableTrueWidth = false;
    protected $pageLimit = 100000;
    protected $optionPrint = "landscape";

    public function getSqlStr($params)
    {
    
    $valOfParams = $this->generateValuesFromParamsReport($params);
    // dd($valOfParams);
    $sql = "    SELECT
                    sp.project_id AS project_id
                    ,sp.id AS sub_project_id
                    ,pj.name AS project_name
                    ,sp.name AS sub_project_name
                    ,prl.id AS prod_routing_link_id
                    ,prl.name AS prod_routing_link_name
                    ,pr.id AS prod_routing_id
                    ,pr.name AS prod_routing_name
                    ,pd.id AS prod_discipline_id
                    ,pd.name AS prod_discipline_name
                    
                    ,ROUND(AVG(pose.worker_number),2) AS avg_worker_number
                    ,ROUND(AVG(pose.total_uom),2) AS avg_total_uom
                    ,ROUND(AVG(pose.total_hours*60),2) AS avg_min
                    ,ROUND(AVG(pose.total_hours*60/(pose.total_uom)),2) AS avg_min_uom

                    FROM sub_projects sp
                        JOIN projects pj ON pj.id = sp.project_id
                        JOIN prod_orders po ON po.sub_project_id = sp.id
                        LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id
                        LEFT JOIN prod_sequences pose ON pose.prod_order_id = po.id
                        LEFT JOIN prod_routing_links prl ON prl.id = pose.prod_routing_link_id
                        LEFT JOIN prod_routing_details prd ON prl.id = prd.prod_routing_link_id AND prd.prod_routing_id = pr.id
                        JOIN prod_disciplines pd ON prl.prod_discipline_id = pd.id
                        WHERE 1 = 1
                        #AND po.sub_project_id IN  (82, 21)
                       # AND pr.id IN (6)
                        #AND prl.id IN (11)
                        AND pose.deleted_by IS NULL";

 if (isset($valOfParams['sub_project_id'])) $sql .= "\n AND po.sub_project_id IN ({{sub_project_id}})";
 if (isset($valOfParams['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id IN ({{prod_routing_id}})";
 if (isset($valOfParams['prod_discipline_id']) && $valOfParams['prod_discipline_id']) $sql .= "\n AND pd.id IN ({{prod_discipline_id}})";
 if (isset($valOfParams['prod_routing_link_id']) && $valOfParams['prod_routing_link_id']) $sql .= "\n AND prl.id IN ({{prod_routing_link_id}})";

                $sql .= "\n GROUP BY project_id, sub_project_id,prod_routing_link_id,prod_routing_id
                ORDER BY project_name, sub_project_name, prod_discipline_name, prod_routing_link_name";
        return $sql;
    }


    protected function getDefaultValueParams($params, $request)
    {
        $params['sub_project_id'] = $this->subProjectId;
        $params['prod_routing_id'] = $this->prodRoutingId;
        return $params;
    }

   
    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
                'hasListenTo' => true,
                'multiple' => true,
            ],
            [
                'title' => 'Production Routing',
                'dataIndex' => 'prod_routing_id',
            ],
            [
                'title' => 'Production Discipline',
                'dataIndex' => 'prod_discipline_id',
                'allowClear' => true,
                // 'validation' => 'required',
                // 'textValidation' => 'Select A Production Discipline',
            ],
            [
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'allowClear' => true,
                // 'hasListenTo' => true,
                'multiple' => true,
            ],
            

        ];
    }

    public function getTableColumns($params, $dataSource)
    {

        $optionLayout = $params['optionPrintLayout'] ?? $this->optionPrint;
        return  [
                [
                    "title" => "Project",
                    "dataIndex" => "project_name",
                    "align" => "left",
                    "width" =>  $optionLayout === 'portrait' ? 110: 80,
                ],
                [
                    "title" => "Sub Project",
                    "dataIndex" => "sub_project_name",
                    "align" => "left",
                    "width" =>  $optionLayout === 'portrait' ? 110: 80,
                ],
                [
                    "title" => "Production Routing",
                    "dataIndex" => "prod_routing_name",
                    "align" => "left",
                    "width" => $optionLayout === 'portrait' ? 180: 222,
                ],
                [
                    "title" => "Production Discipline",
                    "dataIndex" => "prod_discipline_name",
                    "align" => "left",
                    "width" => $optionLayout === 'portrait' ? 150: 190,
                    "hasListenTo" => true,
                ],
                [
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
                    "align" => "left",
                    "width" => $optionLayout === 'portrait' ? 200: 270,
                ],
                [
                    "title" => "m'p <br/> (AVG)",
                    "dataIndex" => "avg_worker_number",
                    "align" => "right",
                    "width" => $optionLayout === 'portrait' ? 100: 110,
                ],
                [
                    "title" => "Total UoM <br/> (AVG)",
                    "dataIndex" => "avg_total_uom",
                    "align" => "right",
                    "width" => $optionLayout === 'portrait' ? 100: 110,
                ],
                [
                    "title" => "min <br/> (AVG)",
                    "dataIndex" => "avg_min",
                    "align" => "right",
                    "width" => $optionLayout === 'portrait' ? 100: 110,
                ],
                [
                    "title" => "min/UoM <br/> (AVG)",
                    "dataIndex" => "avg_min_uom",
                    "align" => "right",
                    "width" => $optionLayout === 'portrait' ? 100: 110,
                ],
            ];

    }

    public function getBasicInfoData($params)
    {
        $prodPouting = Prod_routing::find($params['prod_routing_id'] ?? $this->prodRoutingId)->name;
        $prodDiscipline = isset($params['prod_discipline_id']) ? Prod_discipline::find($params['prod_discipline_id'])->name : '';

        $subProjectName = isset($params['sub_project_id']) ?
        implode(',', Sub_project::whereIn('id', $params['sub_project_id'])
            ->pluck('name')
            ->toArray()) : '';

        $basicInfoData['sub_project_name'] = $subProjectName;
        $basicInfoData['prod_routing_name'] = $prodPouting;
        $basicInfoData['prod_discipline_name'] = $prodDiscipline;
        // dd($basicInfoData);
        return $basicInfoData;
    }


    private function makeDataWidget($dataSource, $params){
        $dataOfManageWidget = $this->makeParamsInManageWidgets($params);
        $dataWidgets = [];
        foreach ($dataSource as $key => $items){
            $array = [];
            foreach ($dataOfManageWidget as $keyInManage => $paramsWidget){
                $result = $this->createDataSourceWidgets($key, $items, $paramsWidget);
                $array[$keyInManage] = $result;
            }
            $dataWidgets[$key] = $array; 
        }
        return $dataWidgets;
    }

    public function changeDataSource($dataSource, $params)
    {
        // $output['tableDataSource'] = $dataSource;
        $items = Report::getItemsFromDataSource($dataSource);
        $groupItems = Report::groupArrayByKey($items,'prod_routing_link_id');
        $data['tableDataSource'] = $dataSource;
        $data['dataWidgets'] = $this->makeDataWidget($groupItems, $params);
        return collect($data);
    }

}
