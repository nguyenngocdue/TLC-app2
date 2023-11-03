<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Http\Controllers\Reports\TraitUpdateBasicInfoDataSource;
use App\Models\Prod_discipline;
use App\Models\Prod_routing;
use App\Models\Project;
use App\Models\Sub_project;
use App\Utils\Support\DateReport;
use App\Utils\Support\Report;
use App\View\Components\Renderer\Report\TraitCreateDataSourceWidget2Columns;
use App\View\Components\Renderer\Report\TraitParamsInManageWidget;

class Prod_sequence_060 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitUpdateBasicInfoDataSource;

    use TraitParamsInManageWidget;
    use TraitCreateDataSourceWidget2Columns;

    protected $mode = '050';
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 62;
    protected $groupByLength = 1;
    protected $prodDisciplineId = 1;

    protected $groupBy = 'prod_discipline_name';
    protected $viewName = 'document-prod-sequence-060';
    protected $type = 'prod_sequence';
    protected $tableTrueWidth = false;
    protected $overTableTrueWidth = false;
    protected $pageLimit = 100000;
    protected $optionPrint = "landscape";

    public function getSqlStr($params)
    {
            $sql = "SELECT 
                        #sp.project_id,
                        #po.id AS pro_order_id,
                        #pj.name AS project_name,
                        #sp.id AS sub_project_id
                        #sp.name AS sub_project_name,
                        #prl.id AS prod_routing_link_id,
                        SUBSTR(pru.date, 1,7) AS month,
                        prl.workplace_id AS workplace_id,
                        ROUND(RAND()*100,2) AS downtime_hours,

                        300 AS head_count,
                        wp.name AS workplace_name,
                        ROUND(SUM(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start))/60/60),2) AS hours,
                        RAND()*ROUND(300/8,2) AS percent_downtime
                    FROM sub_projects sp
                    JOIN projects pj ON pj.id = sp.project_id
                    JOIN prod_orders po ON po.sub_project_id = sp.id
                    LEFT JOIN prod_sequences pse ON pse.prod_order_id = po.id    
                    LEFT JOIN prod_routing_links prl ON prl.id = pse.prod_routing_link_id
                    LEFT JOIN prod_runs pru ON pru.prod_sequence_id = pse.id
                    LEFT JOIN workplaces wp on wp.id = prl.workplace_id
                    WHERE 1 = 1
                        #AND sp.project_id = 5
                        #AND sp.id = 21
                        #AND po.prod_routing_id = 2
                        #AND prl.prod_discipline_id = 2
                        #AND prl.id = 1
                        AND SUBSTR(pru.date,1,10) <= '2023-10-10' 
                        AND SUBSTR(pru.date,1,10) >= '2021-10-10'
                        AND prl.workplace_id IS NOT NULL
                        GROUP BY 
                            #sub_project_id, 
                            workplace_id,
                            month
                        ORDER BY  workplace_name, month";
        return $sql;
    }

  
    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] = DateReport::defaultPickerDate('-1 months');
        $params['project_id'] = $this->projectId;
        $params['sub_project_id'] = $this->subProjectId;
        $params['prod_routing_id'] = $this->prodRoutingId;
        $params['prod_discipline_id'] = $this->prodDisciplineId;
        return $params;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                "title" => "Date",
                "dataIndex" => "picker_date",
                "renderer" => "picker_date",
            ],
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
            ],
            [
                'title' => 'Production Discipline',
                'dataIndex' => 'prod_discipline_id',
                'allowClear' => false,
            ],
            [
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'allowClear' => true,
                'hasListenTo' => true,
                'multiple' => true,
            ],

        ];
    }

    public function getTableColumns($params, $dataSource)
    {

        return [
            [
                "dataIndex" => "month",
                "align" => "left",
                "width" => 150,
            ],
            [
                "dataIndex" => "workplace_name",
                "align" => "left",
                "width" => 150,
            ],
            [
                "dataIndex" => "head_count",
                "align" => "left",
                "width" => 150,
            ],
            [
                "dataIndex" => "hours",
                "align" => "right",
                "width" => 150,
            ],
            [
                "dataIndex" => "downtime_hours",
                "align" => "right",
                "width" => 150,
            ],
            [
                "dataIndex" => "percent_downtime",
                "align" => "right",
                "width" => 150,
            ]
        ];
           
    }

    public function getBasicInfoData($params)
    {
        $projectName = Project::find($params['project_id'] ?? $this->projectId)->name;
        $subProjectName = Sub_project::find($params['sub_project_id'] ?? $this->subProjectId);
        $prodPouting = Prod_routing::find($params['prod_routing_id'] ?? $this->prodRoutingId)->name;
        $prodDiscipline = isset($params['prod_discipline_id']) ? Prod_discipline::find($params['prod_discipline_id'])->name : '';


        $basicInfoData['project_name'] = $projectName;
        $basicInfoData['sub_project_name'] = $subProjectName->name;
        $basicInfoData['prod_routing_name'] = $prodPouting;
        $basicInfoData['prod_discipline_name'] = $prodDiscipline;
        [$fromDate, $toDate] = explode('-', $params['picker_date']);
        $basicInfoData['from_date'] = $fromDate;
        $basicInfoData['to_date'] = $toDate;
        return $basicInfoData;
    }

    private function makeDataWidget($dataSource, $params){
        $dataOfManageWidget = $this->makeParamsInManageWidgets($params);
        $isLineSeresStandard = $dataWidgets['line_series']['standard'] ?? null;
        $dataWidgets = [];
        //two column on chart
        foreach ($dataSource as $key => $items){
            $array = [];
            foreach ($dataOfManageWidget as $keyInManage => $paramsWidget){
                $isLineSeresStandard = $paramsWidget['line_series']['standard'] ?? null;
                if(!$isLineSeresStandard){
                    $result = $this->createDataWidgets2Columns($key, $items, $paramsWidget);
                    $array[$keyInManage] = $result;
                }
            }
            $dataWidgets[$key] = $array; 
            
        }
        // line series chart
        array_walk($dataWidgets, function($values, $key) use(&$dataWidgets,$dataSource, $dataOfManageWidget) {
            foreach($values as $keyWidget => &$item){
                if(isset($dataOfManageWidget[$keyWidget]['line_series'])){
                    $lineSeries = $dataOfManageWidget[$keyWidget]['line_series'];
                    foreach ($lineSeries as $line) {
                        $line = (array)$line;
                        $item['line_series'] = $line;
                        $field =  $line['data_field'];
                        $data = array_column($dataSource[$key], $field);
                        $line['data'] = $data;

                        //set default value (setting in ManageWidget)
                        $line['label'] = $line['data_label'] ?? "undefined";
                        $line['backgroundColor'] = $line['backgroundColor'] ?? "#000000" ;
                        $line['borderColor'] = $line["line_color"] ?? "#660000";
                        $line['fill'] = $line['fill'] ?? false;
                        $line['type'] = $line['type'] ?? "line";
                        $line['tension'] = $line['tension'] ?? 0;
                        $line['borderWidth'] = $line['borderWidth'] ?? 0.8;
                        $line['pointBackgroundColor'] = $line['pointBackgroundColor'] ?? "#000000";

                        $numberOfItems = $item['meta']['numbers'];
                        array_unshift($numberOfItems, (object)$line);
                        $item['meta']['numbers'] =  $numberOfItems;
                    }
                    $item['meta']['count'] = count($item['meta']['numbers']);
                }
            }
            $dataWidgets[$key] = $values;
        });
        // dd($dataWidgets);
        return $dataWidgets;
    }

    private function createWidgetManyColumns($dataSource, $params){
        $dataOfManageWidget = $this->makeParamsInManageWidgets($params);
        $isLineSeresStandard = $dataWidgets['line_series']['standard'] ?? null;
        $dataWidgets = [];

        foreach ($dataOfManageWidget as $keyInManage => $paramsWidget){
            $isLineSeresStandard = $paramsWidget['line_series']['standard'] ?? null;
            if($isLineSeresStandard){
                $dataForManyColumns = $this->createDataWidgetForManyColumns($dataSource, $paramsWidget);
                $dataWidgets[$keyInManage] = $dataForManyColumns;
            }
        }
        // dd($dataWidgets);
        return $dataWidgets;
    }


    public function changeDataSource($dataSource, $params)
    {
        $items = Report::getItemsFromDataSource($dataSource);
        $groupItems = Report::groupArrayByKey($items,'workplace_name');

        $dataWidgets = $this->makeDataWidget($groupItems, $params);
        $data= [];
        foreach ($groupItems as $key => $values){
            $data['render_pages'][$key] = [
                // 'tableDataSource' => collect($values),
                'dataWidgets' => $dataWidgets[$key],
            ];
        }
        $data['tableDataSource'] = collect($items);
        $data['dataWidgetsComparison'] = $this->createWidgetManyColumns($items, $params);
        // dd($data);
        return $data;
    }

}
