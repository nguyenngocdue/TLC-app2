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
use App\Models\Term;
use App\Utils\Support\ModificationDataReport;
use App\Utils\Support\Report;
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
    protected $projectId = 5;
    protected $subProjectId = 21;
    protected $prodRoutingId = 2;
    protected $groupByLength = 1;
    protected $groupBy = 'prod_discipline_name';
    protected $viewName = 'document-prod-sequence-050';
    protected $type = 'prod_sequence';
    protected $tableTrueWidth = true;
    protected $overTableTrueWidth = true;
    protected $pageLimit = 100000;
    protected $optionPrint = "landscape";

    public function getSqlStr($params)
    {
        $sql = "SELECT 	project_id,
                    project_name,
                    sub_project_id,
                    sub_project_name,
                    prod_routing_id,
                    prod_routing_name,
                    prod_routing_link_id,
                    prod_routing_link_name,
                    prod_discipline_id,
                    prod_discipline_name,
                    uom_name,
                    FORMAT(AVG(man_power),2) AS man_power,
                    AVG(total_uom) AS total_uom,
                    AVG(total_hours) AS total_hours,
                    SUM(count_pru_date) AS count_pru_date,
                    AVG(total_hours)*60 / SUM(count_pru_date) AS min_on_day,
                    AVG(total_hours)*60 / AVG(total_uom) AS min_on_set,
                    uom_name
                    FROM (SELECT
                        sp.project_id AS project_id,
                        pj.name AS project_name,
                        sp.id AS sub_project_id,
                        sp.name AS sub_project_name,
                        po.id AS pro_order_id,
                        pr.id AS prod_routing_id,
                        pr.name AS prod_routing_name,
                        prl.id AS prod_routing_link_id,
                        prl.name AS prod_routing_link_name,
                        pd.id AS prod_discipline_id, 
                        pd.name AS prod_discipline_name,
                        pse.total_hours AS total_hours,
                        pse.worker_number AS man_power,
                        pse.total_uom AS total_uom,
                        pse.total_man_hours AS total_man_hours,
                        COUNT(DISTINCT pru.date) AS count_pru_date,
                        terms.name AS uom_name
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
                        JOIN
                            prod_disciplines pd ON prl.prod_discipline_id = pd.id
                        LEFT JOIN
                            terms terms ON pse.uom_id = terms.id
                        LEFT JOIN prod_runs pru ON pru.prod_sequence_id = pse.id
                    WHERE 1 = 1
                        AND pse.deleted_by IS NULL
                        AND sp.project_id = {{project_id}}
                        AND sp.id = {{sub_project_id}}
                        AND pse.status IN ('in_progress', 'finished')";
        if (isset($params['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id = {{prod_routing_id}}";
        if (isset($params['prod_routing_link_id'])) $sql .= "\n AND pse.prod_routing_link_id = {{prod_routing_link_id}}";
        if (isset($params['prod_discipline_id']))  $sql .= "\n AND prl.prod_discipline_id = {{prod_discipline_id}}";

        $sql .= "\n GROUP BY
                                    pj.name,
                                    sp.id,
                                    sp.name,
                                    po.id
                                    )AS tb1
                                GROUP BY
                                    project_id, uom_name";
        return $sql;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['project_id'] = $this->projectId;
        $params['sub_project_id'] = $this->subProjectId;
        $params['prod_routing_id'] = $this->prodRoutingId;
        return $params;
    }

    private function updateDataForPivotChart($data)
    {
        $values = array_values($data->toArray());
        $primaryData = reset($values);
        $unit = isset($primaryData->uom_name) ? $primaryData->uom_name : "no name uom_name";
        $dataToRender = [
            'man_power' => $primaryData->man_power,
            'total_uom' => $primaryData->total_uom,
            'min_on_day' => $primaryData->min_on_day,
            'min_on_set' => $primaryData->min_on_set,
        ];

        // information for meta data
        $labelName = ['Man-power', $unit.'/day', 'min/day', 'min/'.$unit];
        $labels = StringReport::arrayToJsonWithSingleQuotes($labelName);
        $numbers = StringReport::arrayToJsonWithSingleQuotes(array_values($dataToRender));
        $max = max(array_values($dataToRender));
        $count = count($dataToRender);
        $meta = [
            'labels' => $labels,
            'numbers' => $numbers,
            'max' => $max,
            'count' => $count
        ];

        // information for metric data
        $metric = [];
        array_walk($dataToRender, function ($value, $key) use (&$metric) {
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
            'titleY' => "",
        ];

        // Set data for widget
        $widgetData =  [
            "title_a" => "UoM",
            "title_b" => "by Hours",
            'meta' => $meta,
            'metric' => $metric,
            'chartType' =>'bar',
            'titleChart' => '',
            'dimensions' => $dimensions,
            
        ];

        // add widget to dataSource
        $data = ['tableDataSource' => $data];
        $data['widget_01'] = $widgetData;
        // dump($data);
        return $data;
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
            ],
            [
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'allowClear' => true,
                'hasListenTo' => true,
            ],

        ];
    }

    public function getTableColumns($params, $dataSource)
    {
        $data = $dataSource instanceof Collection ? $dataSource->toArray() : $dataSource;
        $data = reset($data);
        $unit = isset($data['uom_name']) && ($x = $data['uom_name']) ? $x : '<small class="text-orange-300">Unknown Unit</small>';
        $optionPrint = $params['optionPrint'] ?? $this->optionPrint;
        return
            [
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
                    "width" => 80,
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
                    "width" => 75,
                ],
                [
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
                    "align" => "left",
                    "width" => 145,
                ],
                [
                    "title" => "Man-power <br/>(AVG)",
                    "dataIndex" => "man_power",
                    "align" => "right",
                    "width" => 78,
                    // "footer" => "agg_sum",
                ],
                [
                    "title" => $unit . "/day <br/>(AVG)",
                    "dataIndex" => "total_uom",
                    "align" => "right",
                    "width" => 75,
                    // "footer" => "agg_sum",
                ],
                [
                    "title" => "min/day <br/>(AVG)",
                    "dataIndex" => "min_on_day",
                    "align" => "right",
                    "width" => 75,
                    // "footer" => "agg_sum",
                ],
                [
                    "title" => "min/$unit <br/>(AVG)",
                    "dataIndex" => "min_on_set",
                    "align" => "right",
                    "width" =>  75,
                    // "footer" => "agg_sum",
                ]
            ];
    }

    public function getBasicInfoData($params)
    {
        $projectName = Project::find($params['project_id'] ?? $this->projectId)->name;
        $subProjectName = Sub_project::find($params['sub_project_id'] ?? $this->subProjectId);
        $prodPouting = Prod_routing::find($params['prod_routing_id'] ?? $this->prodRoutingId)->name;
        $prodDiscipline = isset($params['prod_discipline_id']) ? Prod_discipline::find($params['prod_discipline_id'])->name : '';

        $prodRoutingLink = isset($params['prod_routing_link_id']) ?
            implode(',', Prod_routing_link::find($params['prod_routing_link_id'])
                ->pluck('name')
                ->toArray()) : '';

        $basicInfoData = [];
        $basicInfoData['project'] = $projectName;
        $basicInfoData['sub_project'] = $subProjectName->name;
        $basicInfoData['prod_routing'] = $prodPouting;
        $basicInfoData['prod_routing_link'] = $prodRoutingLink;
        $basicInfoData['prod_discipline'] = $prodDiscipline;
        return $basicInfoData;
    }



    public function changeDataSource($dataSource, $params)
    {
        $dataSource = self::updateDataForPivotChart($dataSource);
        // dump($dataSource);
        return $dataSource;
    }
}
