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
use App\Utils\Support\StringReport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Prod_sequence_050 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitUpdateBasicInfoDataSource;

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
        $sql = "SELECT project_id,
        project_name,
        tb1.sub_project_id,
        sub_project_name,
        tb1.prod_routing_id,
        prod_routing_name,
        tb1.prod_routing_link_id,
        prod_routing_link_name,
        tb1.prod_discipline_id,
        prod_discipline_name,
        tb1.prod_order_id,
        prod_order_name,
            FORMAT((pse.worker_number),2) AS man_power,
			(pse.total_hours) AS total_hours,
            FORMAT(IF((pse.total_uom)/count_pru_date, (pse.total_uom)/count_pru_date, NULL),2) AS total_uom,
        terms.name AS uom_name,
        FORMAT(IF((pse.total_hours)*60 / count_pru_date, (pse.total_hours)*60 / count_pru_date, NULL),2) AS min_on_day,
        FORMAT(IF((pse.total_hours)*60 / ((pse.total_uom)/count_pru_date), (pse.total_hours)*60 / ((pse.total_uom)/count_pru_date), NULL),2) AS min_on_set
        FROM (SELECT
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
                    pse.id AS prod_sequences_id,
                    po.id AS prod_order_id,
              		po.name AS prod_order_name,
                    prl.standard_uom_id AS standard_uom_id,
                    COUNT(DISTINCT pru.date) AS count_pru_date
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
                    JOIN
                        prod_routing_links prl ON prl.id = pse.prod_routing_link_id
                    JOIN prod_disciplines pd ON prl.prod_discipline_id = pd.id
                    LEFT JOIN prod_runs pru ON pru.prod_sequence_id = pse.id
                WHERE 1 = 1
                        AND pse.deleted_by IS NULL
                        AND sp.project_id = {{project_id}}
                        AND sp.id = {{sub_project_id}}";
                        #AND pse.status IN ('in_progress', 'finished')";
        if (isset($params['prod_routing_id'])                        ) $sql .= "\n AND po.prod_routing_id = {{prod_routing_id}}";
        if (isset($params['prod_discipline_id']))  $sql .= "\n AND prl.prod_discipline_id = {{prod_discipline_id}}";
        if (isset($params['prod_routing_link_id'])) $sql .= "\n AND pse.prod_routing_link_id = {{prod_routing_link_id}}";

        $sql .= "\n GROUP BY
                            pj.name,
                            sp.id,
                            sp.name,
                            prl.id,
                            prod_sequences_id
                        ORDER BY pj.name, sp.name, pr.name, pd.name, prl.name )AS tb1
                        LEFT JOIN prod_sequences pse ON pse.id = tb1.prod_sequences_id
                        LEFT JOIN terms terms ON tb1.standard_uom_id = terms.id
                        GROUP BY
                        prod_routing_link_id,uom_name, tb1.prod_order_id
                        ORDER BY project_name, sub_project_name, prod_routing_name,
                        prod_discipline_name, prod_routing_link_name, prod_order_name";

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
        $params['project_id'] = $this->projectId;
        $params['sub_project_id'] = $this->subProjectId;
        $params['prod_routing_id'] = $this->prodRoutingId;
        $params['prod_discipline_id'] = $this->prodDisciplineId;
        return $params;
    }

    private function updateDataForPivotChart($dataSource)
    {
        $result = [];
        // dd($dataSource);
        foreach ($dataSource as $prodRoutingLinkId => $data) {
            $items = array_values($data->toArray());
            // if(empty($items)) continue;
            $primaryData = reset($items);
            $unit = isset($primaryData['uom_name']) ? $primaryData['uom_name'] : "(Unknown Unit)";
            // dump($unit);


            $typeCharts = ['man_power', 'total_uom', 'min_on_day', 'min_on_set'];
            $titleCharts = ['Man Power (AVG)', $unit.'/Day (AVG)', 'min/Day (AVG)', 'min/'.$unit .'(AVG)'];
            foreach($typeCharts as $key => $typeChart){
                // information for meta data
                $labelName = array_map(fn($item) => $item['prod_order_name'], $items);
                $dataNumber = array_map(fn($item) => $item[$typeChart], $items);
                $labels = StringReport::arrayToJsonWithSingleQuotes($labelName);
                $numbers = StringReport::arrayToJsonWithSingleQuotes(array_values($dataNumber));
                $max = max(array_values($dataNumber));
                $count = count($dataNumber);
                $meta = [
                    'labels' => $labels,
                    'numbers' => $numbers,
                    'max' => $max,
                    'count' => $count
                ];
    
                // information for metric data
                $metric = [];
                array_walk($dataNumber, function ($value, $key) use (&$metric) {
                    return $metric[] = (object) [
                        'meter_id' => $key,
                        'metric_name' => $value
                    ];
                });
    
                // relate to dimensions AxisX and AxisY
                $dimensions = [
                    'scaleMaxY' => null,
                    'fontSizeAxisXY' => 16,
                    'fontSize' => 14,
                    'titleX' => "",
                    'titleY' => "%WIP",
                    #'height' => 200,
                    #'width' => 400,
                    'scaleMaxY' => ceil((int)$max*1.5),
                    'titleChart' => $titleCharts[$key],
                    'fontSizeTitleChart' => 20,
                    'barPercentage' => 0.5,
                    'widthBar' => 100,
                    'dataLabelOffset' => 10,

                ];
    
                // Set data for widget
                $widgetData =  [
                    "title_a" => $typeChart.'_'.$prodRoutingLinkId,
                    "title_b" => $typeChart.$prodRoutingLinkId,
                    'meta' => $meta,
                    'metric' => $metric,
                    'chartType' =>'bar',
                    'dimensions' => $dimensions,
                    
                ];
                $dataOutput['widget_'. $key] = $widgetData;
                // dd($data);  
                $dataOutput['tableDataSource'] =  $data;
                $result[$prodRoutingLinkId] = $dataOutput;
            };
        }
        // dd($result);
        return collect($result);



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
                'validation' => 'required',
                'hasListenTo' => true,
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
            $data = $dataSource['render_pages']->toArray();
            foreach ($data as $key => $values){
                    $item = $values instanceof Collection ? $values['tableDataSource']->toArray() : $values['tableDataSource']->first();
                    $unit = isset($item['uom_name']) && (!is_null($item['uom_name'])) ? $item['uom_name'] : '<small class="text-orange-300">Unknown Unit</small>';
                    $tableColumns[$key] =  [
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
                            "width" =>  $optionLayout === 'portrait' ? 110: 80,
                        ],
                        [
                            "title" => "Production Routing",
                            "dataIndex" => "prod_routing_name",
                            "align" => "left",
                            "width" => $optionLayout === 'portrait' ? 250: 220,
                        ],
                        [
                            "title" => "Production Discipline",
                            "dataIndex" => "prod_discipline_name",
                            "align" => "left",
                            "width" => $optionLayout === 'portrait' ? 200: 75,
                            "hasListenTo" => true,
                        ],
                        [
                            "title" => "Production Routing Link",
                            "dataIndex" => "prod_routing_link_name",
                            "align" => "left",
                            "width" => $optionLayout === 'portrait' ? 300: 250,
                        ],
                        [
                            "title" => "Production Order",
                            "dataIndex" => "prod_order_name",
                            "align" => "left",
                            "width" =>  $optionLayout === 'portrait' ? 150: 138,
                        ],
                        [
                            "title" => "Man Power <br/>(AVG)",
                            "dataIndex" => "man_power",
                            "align" => "right",
                            "width" => 110,
                            // "footer" => "agg_sum",
                        ],
                        [
                            "title" => $unit . "/Day <br/>(AVG)",
                            "dataIndex" => "total_uom",
                            "align" => "right",
                            "width" => 110,
                            // "footer" => "agg_sum",
                        ],
                        [
                            "title" => "min/Day <br/>(AVG)",
                            "dataIndex" => "min_on_day",
                            "align" => "right",
                            "width" => 110,
                            // "footer" => "agg_sum",
                        ],
                        [
                            "title" => "min/$unit <br/>(AVG)",
                            "dataIndex" => "min_on_set",
                            "align" => "right",
                            "width" =>  110,
                            // "footer" => "agg_sum",
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

        $basicInfoData['project'] = $projectName;
        $basicInfoData['sub_project'] = $subProjectName->name;
        $basicInfoData['prod_routing'] = $prodPouting;
        $basicInfoData['prod_discipline'] = $prodDiscipline;
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

    public function getDataSource($params)
    {
        $prodRoutingLinkIds = array_keys($this->getProdRoutingLinks($params));
        $arraySqlStr = $this->generateArraySqlFromSqlStr($prodRoutingLinkIds, $params);  
        $data = [];
        $allProdOrdersHaveNotSeq = $this->getAllProdOrders($params);

        foreach ($arraySqlStr as $k => $sql) {
            if (is_null($sql) || !$sql) return collect();
            $params['prod_routing_link_id'] = $k; // k is an id of prod_routing_link_id 
            $sql = $this->getSql($params);
            $sqlData = DB::select(DB::raw($sql));

            if(empty($sqlData)) continue;
            if(!empty($sqlData)) {
                $firstSqlData = $sqlData[0];
                $temps = [];
                foreach ($allProdOrdersHaveNotSeq as $values) {
                    $arr = [
                        'prod_routing_link_name' => $firstSqlData->prod_routing_link_name,
                        'prod_routing_link_id' => $firstSqlData->prod_routing_link_id,
                        'prod_discipline_id' => $firstSqlData->prod_discipline_id,
                        'prod_discipline_name' => $firstSqlData->prod_discipline_name,
                        // ...other properties from $values
                    ];
                    $arr = array_merge((array)$values, $arr);
                    $temps[] =  (object)$arr;
                }
    
                $data[$k] = collect([...$sqlData, ...$temps]);  
            }

        }
        // dd($data);
        return $data;
    }

    private function getProdRoutingLinks($params){
        $prodRoutingLinkIds = isset($params['prod_routing_link_id']) ?
        $params['prod_routing_link_id']:Prod_discipline::find($params['prod_discipline_id'])->getProdRoutingLink()->pluck('id')->toArray();
        $prodRoutingLinks = Prod_routing_link::whereIn('id', $prodRoutingLinkIds)->get()->pluck('name', 'id')->toArray();
        return $prodRoutingLinks;
    }   



    public function changeDataSource($dataSource, $params)
    {
        // dd($dataSource);
        foreach ($dataSource as $k => $values) $dataSource[$k] = $this->addTooltip($values);
        $dataSource = self::updateDataForPivotChart($dataSource);

        $prodRoutingLinks = $this->getProdRoutingLinks($params);
        $prodRoutingLinks = array_intersect_key($prodRoutingLinks, array_flip(array_keys($dataSource->toArray())));
        $output['render_pages'] = $dataSource;
        $output['table_of_contents'] = $prodRoutingLinks;

        return $output;
    }
}
