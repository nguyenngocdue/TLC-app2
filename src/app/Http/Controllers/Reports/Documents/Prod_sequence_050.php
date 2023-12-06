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
use App\View\Components\Renderer\Report\TraitCreateDataSourceWidget;
use App\View\Components\Renderer\Report\TraitParamsInManageWidget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Prod_sequence_050 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitUpdateBasicInfoDataSource;

    use TraitParamsInManageWidget;
    use TraitCreateDataSourceWidget;

    protected $mode = '050';
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 62;
    protected $groupByLength = 1;
    protected $prodDisciplineId = 1;

    protected $groupBy = 'prod_discipline_name';
    protected $viewName = 'document-prod-sequence-050';
    protected $type = 'prod_sequence';
    protected $tableTrueWidth = true;
    protected $overTableTrueWidth = false;
    protected $pageLimit = 100000;
    protected $optionPrint = "landscape";

    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);
        $endDate = $valOfParams['picker_date']['end'];
        $startDate = $valOfParams['picker_date']['start'];
        $sql = "SELECT
                    sp.project_id AS project_id,
                    pj.name AS project_name,
                    sp.id AS sub_project_id,
                    sp.name AS sub_project_name,
                    pr.id AS prod_routing_id,
                    pr.name AS prod_routing_name,
                    prl.id AS prod_routing_link_id,
                    prl.name AS prod_routing_link_name,
                    pd.id AS prod_discipline_id, 
                    pd.name AS prod_discipline_name,
                    #pse.id AS prod_sequences_id,
                    #po.id AS prod_order_id,
                    #po.name AS prod_order_name,
                    prd.order_no AS order_no,
                    MAX(pru.date) AS pru_date,
                    terms.name AS uom_name,
                    ROUND(AVG(pru.worker_number),2) AS man_power_on_day,
                    ROUND(SUM(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)))/60,2) AS min_on_day,
                    SUM(pru.production_output) AS total_uom_on_day,
                    ROUND((SUM(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)))/60)/ SUM(pru.production_output),2) AS min_on_set_on_day
                    FROM
                        sub_projects sp
                    JOIN projects pj ON pj.id = sp.project_id
                    JOIN prod_orders po ON po.sub_project_id = sp.id
                    JOIN prod_routings pr ON pr.id = po.prod_routing_id
                    LEFT JOIN prod_sequences pse 	ON pse.prod_order_id = po.id 
                                                    AND pse.sub_project_id = sp.id
                    JOIN prod_routing_links prl ON prl.id = pse.prod_routing_link_id
                    JOIN prod_disciplines pd ON prl.prod_discipline_id = pd.id
                    LEFT JOIN prod_runs pru ON pru.prod_sequence_id = pse.id
                    LEFT JOIN prod_routing_details prd ON pr.id = prd.prod_routing_id
                                                    AND prl.id = prd.prod_routing_link_id
                    LEFT JOIN terms terms ON prl.standard_uom_id = terms.id
                WHERE 1 = 1
                        AND pse.deleted_by IS NULL
                        #AND sp.project_id = 8
                        #AND sp.id = 107
                        #AND po.prod_routing_id = 62
                        #AND prl.prod_discipline_id = 1
                        #AND pse.prod_routing_link_id = 307
                        AND sp.project_id = {{project_id}}
                        AND sp.id = {{sub_project_id}}
                        AND SUBSTR(pru.date, 1, 10) <= '$endDate'
                        AND SUBSTR(pru.date, 1, 10) >= '$startDate'
                        ";
                // if(isset($params['picker_date'])) $sql .= "\n AND pru.date >= $params['picker_date']['start']";
                if (isset($params['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id = {{prod_routing_id}}";
                if (isset($params['prod_discipline_id']))  $sql .= "\n AND prl.prod_discipline_id = {{prod_discipline_id}}";
                if (isset($params['prod_routing_link_id'])) $sql .= "\n AND pse.prod_routing_link_id IN ({{prod_routing_link_id}})";
                 

                       $sql .="\n GROUP BY
                            project_id, sub_project_id, prod_routing_id, prod_routing_link_id, pru.date
                            #, prod_sequences_id
                        ORDER BY  order_no";

        return $sql;
    }

    private function getAllProdOrders($params){
        $sql = "SELECT
                    sp.project_id,
                    pj.name AS project_name,
                    sp.id AS sub_project_id,
                    sp.name AS sub_project_name,
                    pr.id AS prod_routing_id,
                    pr.name AS prod_routing_name,
                    po.id AS prod_order_id,
                    po.name AS prod_order_name,
                    #prd.order_no AS order_no,
                    NULL AS man_power,
                    NULL AS total_hours,
                    NULL AS uom_name,
                    NULL AS min_on_day,
                    NULL AS min_on_set,
                    NULL AS total_uom
                FROM
                    sub_projects sp
                JOIN
                    projects pj ON pj.id = sp.project_id
                JOIN
                    prod_orders po ON po.sub_project_id = sp.id
                JOIN
                    prod_routings pr ON pr.id = po.prod_routing_id
                LEFT JOIN
                    prod_sequences pse ON pse.prod_order_id = po.id
                #LEFT JOIN prod_routing_details prd  ON {{prod_routing_id}} = prd.prod_routing_id
                
                WHERE 1 = 1";
            $sql .= "\n 
                        AND pse.id IS NULL
                        AND pse.deleted_by IS NULL
                        AND sp.project_id = {{project_id}}
                        AND sp.id = {{sub_project_id}}
                        AND po.prod_routing_id = {{prod_routing_id}}
                        ";

        $sql = $this->preg_match_all($sql, $params);
        $sqlData = DB::select(DB::raw($sql));
        return ($sqlData);
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

        $optionLayout = $params['optionPrintLayout'] ?? $this->optionPrint;
        $tableColumns = [];
        if(isset($dataSource['render_pages'])){
            $data = $dataSource['render_pages'];
            foreach ($data as $key => $values){
                    $item = $values instanceof Collection ? $values['tableDataSource']->toArray() : $values['tableDataSource']->first();
                    $unit = isset($item['uom_name']) && (!is_null($item['uom_name'])) ? $item['uom_name'] : '<small class="text-orange-300">Unknown Unit</small>';
                    $tableColumns[$key] =  [
                        [
                            "title" => "Date",
                            "dataIndex" => "pru_date",
                            "align" => "center",
                            "width" => 120,
                        ],
                        [
                            "title" => "Project",
                            "dataIndex" => "project_name",
                            "align" => "left",
                            "width" => 80,
                        ],
                        [
                            "title" => "Sub Project",
                            "dataIndex" => "sub_project_name",
                            "align" => "left",
                            "width" =>  $optionLayout === 'portrait' ? 110: 120,
                        ],
                        [
                            "title" => "Production Routing",
                            "dataIndex" => "prod_routing_name",
                            "align" => "left",
                            "width" => $optionLayout === 'portrait' ? 250: 280,
                        ],
                        // [
                        //     "title" => "Production Discipline",
                        //     "dataIndex" => "prod_discipline_name",
                        //     "align" => "left",
                        //     "width" => $optionLayout === 'portrait' ? 200: 193,
                        //     "hasListenTo" => true,
                        // ],
                        [
                            "title" => "Production Routing Link",
                            "dataIndex" => "prod_routing_link_name",
                            "align" => "left",
                            "width" => $optionLayout === 'portrait' ? 300: 263,
                        ],
                        [
                            "title" => "Man Power <br/>(AVG)",
                            "dataIndex" => "man_power_on_day",
                            "align" => "right",
                            "width" => 110,
                            "footer" => "agg_avg",
                        ],
                        [
                            "title" => $unit . "/Day <br/>(AVG)",
                            "dataIndex" => "total_uom_on_day",
                            "align" => "right",
                            "width" => 110,
                            "footer" => "agg_avg",
                        ],
                        [
                            "title" => "min/Day <br/>(AVG)",
                            "dataIndex" => "min_on_day",
                            "align" => "right",
                            "width" => 110,
                            "footer" => "agg_avg",
                        ],
                        [
                            "title" => "min/$unit <br/>(AVG)",
                            "dataIndex" => "min_on_set_on_day",
                            "align" => "right",
                            "width" =>  90,
                            "footer" => "agg_avg",
                        ]
                    ];
                }
        };

        // dd($tableColumns);
        return $tableColumns;
           
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

    private function generateArraySqlFromSqlStr($prodRoutingLinkIds, $params) {
        $arraySqlStr = [];
        foreach ($prodRoutingLinkIds as $prodRoutingLinkId){
            $params['prod_routing_link'] = $prodRoutingLinkId;
            $arraySqlStr[$prodRoutingLinkId] = $this->getSqlStr($params);
        }
        return $arraySqlStr;
    }


    private function makeDataWidget($dataSource, $params){
        // dd($params);
        $dataOfManageWidget = $this->makeParamsInManageWidgets($params);
        // dd($dataOfManageWidget);
        $dataWidgets = [];
        foreach ($dataSource as $key => $items){
            $array = [];
            foreach ($dataOfManageWidget as $keyInManage => $paramsWidget){
                $result = $this->createDataSourceWidgets($key, $items, $paramsWidget);
                $array[$keyInManage] = $result;
            }
            $dataWidgets[$key] = $array; 
        }
        // dd($dataWidgets);
        return $dataWidgets;
    }



    public function changeDataSource($dataSource, $params)
    {
        $items = Report::getItemsFromDataSource($dataSource);
        $groupItems = Report::groupArrayByKey($items,'prod_routing_link_id');
        
        $prodRoutingLinks = $this->getProdRoutingLinks($params);
        $tableOfContents = [];
        foreach(array_keys($groupItems) as $key) $tableOfContents[$key] = $prodRoutingLinks[$key];
        
        $dataWidgets = $this->makeDataWidget($groupItems, $params);
        // dd($dataWidgets);
        $data= [];
        $fields = ['man_power_on_day', 'min_on_day', 'total_uom_on_day', 'min_on_set_on_day'];
        foreach ($groupItems as $key => $values){

            // show minus icon when value is null or 0
            $values = array_map(function($item) use ($fields){
                foreach ($fields as $field){
                    if (is_null($item[$field]) || !$item[$field]){
                        // $item[$field] = (object)['value' =>'<i class="fa-light fa-minus"></i>'];
                        $item[$field] = (object)['value' =>'0.00'];
                    }
                }
                return $item;
            }, $values);
            $data['render_pages'][$key] = [
                    'tableDataSource' => collect($values),
                    'dataWidgets' => $dataWidgets[$key],
               ];
        }
        $data['table_of_contents'] = $tableOfContents;
        return $data;
    }
}
