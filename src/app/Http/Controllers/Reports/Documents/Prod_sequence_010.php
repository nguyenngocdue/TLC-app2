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
use App\Utils\Support\ModificationDataReport;
use App\Utils\Support\ParameterReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Prod_sequence_010 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitUpdateBasicInfoDataSource;

    protected $mode = '010';
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 62;
    protected $groupByLength = 1;
    protected $groupBy = 'prod_discipline_name';
    protected $viewName = 'document-prod-sequence-010';
    protected $type = 'prod_sequence';
    protected $tableTrueWidth = true;
    protected $pageLimit = 100000;
    protected $optionPrint = "landscape";

    public function getSqlStr($params)
    {
        $strProdDisciplineIds = ParameterReport::getStringIds('prod_discipline_id');
        $sql = "SELECT
                        targetTb.project_name,
                        targetTb.sub_project_name,
                        targetTb.prod_routing_link_name,
                        targetTb.project_id,
                        targetTb.sub_project_id,    
                        targetTb.prod_routing_link_id,
                        targetTb.prod_routing_id,
                        targetTb.prod_discipline_id,
                        targetTb.prod_routing_link_desc,
                        targetTb.sub_project_desc,
                        order_no,

                        FORMAT(targetTb.target_hours, 2) AS target_hours,
                        FORMAT(actualTb.hours,2) AS hours,
                        FORMAT(round(targetTb.target_hours - actualTb.hours,2),2) AS vari_hours,
                        FORMAT(100 - round((actualTb.hours / targetTb.target_hours)*100,2),2) AS percent_vari_hours,
                    
                        FORMAT(targetTb.target_man_hours,2) AS target_man_hours,
                        FORMAT(actualTb.man_hours,2) AS man_hours,
                        FORMAT(round(targetTb.target_man_hours - actualTb.man_hours ,2),2) AS vari_man_hours,
                        FORMAT(100 - round((actualTb.man_hours / targetTb.target_man_hours)*100,2),2) AS percent_vari_man_hours,
                    
                        FORMAT(targetTb.target_man_power,2) AS target_man_power,
                        FORMAT(actualTb.man_power,2) AS man_power,
                        FORMAT(round(targetTb.target_man_power - actualTb.man_power,2),2) AS vari_man_power,
                        FORMAT(100 - round((actualTb.man_power / targetTb.target_man_power)*100,2),2) AS percent_vari_man_power
                    FROM
                        (SELECT
                        project_id, project_name,sub_project_id,prod_routing_link_desc, sub_project_desc, 
                        sub_project_name, prod_routing_id, prod_discipline_id, prod_routing_link_name
                        ,prod_routing_link_id
                        ,order_no
                        ,SUM(target_hours) AS target_hours
                        ,SUM(target_man_hours) AS target_man_hours
                        ,AVG(target_man_power) AS target_man_power
                        FROM  (SELECT 
                                    sp.project_id,
                                    prd.order_no AS order_no,
                                    sp.id AS sub_project_id,
                                    pj.name project_name,
                                    sp.name AS sub_project_name,
                                    (po.id) AS prod_order_id,
                                    sp.description AS sub_project_desc,
                                    prl.id AS prod_routing_link_id,
                                    prl.name AS prod_routing_link_name,
                                    prl.description AS prod_routing_link_desc,
                                    prl.prod_discipline_id AS prod_discipline_id,
                                    po.prod_routing_id AS prod_routing_id,
                                    (prd.target_hours) AS target_hours,
                                    (prd.target_man_hours) AS target_man_hours,    
                                    (prd.target_man_power) AS target_man_power
                        FROM sub_projects sp
                        JOIN prod_orders po ON po.sub_project_id = sp.id";
        // if (isset($params['prod_order_id'])) $sql .= "\n AND po.id IN ({{prod_order_id}})";
        $sql .= "\n LEFT JOIN prod_sequences pose ON pose.prod_order_id = po.id
                        LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id AND pr.id IN ($strProdDisciplineIds)
                        JOIN prod_routing_links prl ON prl.id = pose.prod_routing_link_id
                        LEFT JOIN prod_routing_details prd ON prl.id = prd.prod_routing_link_id 
                                                        AND prd.prod_routing_id = pr.id
                        LEFT JOIN projects pj ON pj.id = sp.project_id
                        LEFT JOIN prod_runs pru ON pru.prod_sequence_id = pose.id
                        WHERE 1 = 1
                            AND pose.deleted_by IS NULL
                            AND sp.project_id = {{project_id}}
                            AND sp.id = {{sub_project_id}}
                            AND pose.status IN ('in_progress', 'finished', 'on_hold')
                            AND po.status IN ('in_progress', 'finished', 'on_hold')
                            AND SUBSTR(pru.date, 1, 10) <= '{{picker_date}}'";
        if (isset($params['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id = {{prod_routing_id}}";
        if (isset($params['prod_routing_link_id'])) $sql .= "\n AND pose.prod_routing_link_id IN ({{prod_routing_link_id}})";
        if (isset($params['prod_discipline_id']))  $sql .= "\n AND prl.prod_discipline_id IN ({{prod_discipline_id}})";

        $sql .= "\n  GROUP BY prod_routing_link_id, prod_order_id ) AS tb1
                    GROUP BY prod_routing_link_id) AS targetTb
                    JOIN
                        (SELECT 
                        prod_routing_link_id
                        ,ROUND(AVG(man_power),2) AS man_power
                        ,ROUND(AVG(hours)*COUNT(prod_order_id),2) AS hours
                        ,ROUND(AVG(man_hours)*COUNT(prod_order_id),2) AS man_hours
                                FROM (SELECT 
                                        sp.project_id,
                                        sp.id AS sub_project_id,
                                        prl.id AS prod_routing_link_id,
                                        po.id AS prod_order_id,
                                        (pru.worker_number) AS man_power
                                        ,SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60, 2)) AS hours
                                        ,ROUND((pru.worker_number)*SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start))/ 60/60,2)),2) AS man_hours
                                    FROM sub_projects sp
                                    JOIN prod_orders po ON po.sub_project_id = sp.id
                                    LEFT JOIN prod_sequences pose ON pose.prod_order_id = po.id
                                    JOIN prod_routing_links prl ON prl.id = pose.prod_routing_link_id
                                    JOIN prod_runs pru ON pru.prod_sequence_id = pose.id
                                    JOIN prod_disciplines pd ON prl.prod_discipline_id = pd.id";

        if (isset($params['prod_routing_link_id'])) $sql .= "\n AND pose.prod_routing_link_id IN ({{prod_routing_link_id}})";
        $sql .= "\n         WHERE 1 = 1
                                    AND sp.project_id = '{{project_id}}'
                                    AND sp.id = '{{sub_project_id}}'
                                    AND po.prod_routing_id = '{{prod_routing_id}}'
                                    AND pose.status IN ('in_progress', 'finished', 'on_hold')
                                    AND po.status IN ('in_progress', 'finished', 'on_hold')
                                    AND SUBSTR(pru.date, 1, 10) <= '{{picker_date}}'
                                    AND pose.deleted_by IS NULL
                                    #AND prl.id = 15
                                GROUP BY prod_routing_link_id , po.id, pru.worker_number) AS tb2
                            GROUP BY prod_routing_link_id) AS actualTb
                    ON 
                        actualTb.prod_routing_link_id = targetTb.prod_routing_link_id
                        ORDER BY order_no
                        ;";
        return $sql;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] = date('d/m/Y');
        $params['project_id'] = $this->projectId;
        $params['sub_project_id'] = $this->subProjectId;
        $params['prod_routing_id'] = $this->prodRoutingId;
        return $params;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Date',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
                'singleDatePicker' => true,
                'validation' => 'required|date_format:d/m/Y',
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
                'validation' => 'required',
            ],
            [
                'title' => 'Production Discipline',
                'dataIndex' => 'prod_discipline_id',
                'allowClear' => true,
                //'multiple' => true,
                // 'validation' => 'required',
            ],
            [
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'allowClear' => true,
                'multiple' => true,
                'hasListenTo' => true,
                // 'validation' => 'required',
            ],
        ];
    }

    public function getTableColumns($params, $dataSource)
    {
        return
            [
                [
                    "title" => "Sub Project",
                    "dataIndex" => "sub_project_name",
                    "align" => "left",
                    "width" => 80,
                    'fixed' => 'left'
                ],
                [
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
                    "align" => "left",
                    "width" => 156,
                    'fixed' => 'left'
                ],
                [
                    "title" => "Man-power <br/>(AVG)",
                    "dataIndex" => "target_man_power",
                    "align" => "right",
                    "width" => 70,
                    "colspan" => 4,

                ],
                [
                    "dataIndex" => "man_power",
                    "align" => "right",
                    "width" => 70,
                ],
                [
                    "dataIndex" => "vari_man_power",
                    "align" => "right",
                    "width" => 70,

                ],
                [
                    "dataIndex" => "percent_vari_man_power",
                    "align" => "right",
                    "width" => 70,

                ],

                [
                    "title" => "Hours <br/>(SUM)",
                    "dataIndex" => "target_hours",
                    "align" => "right",
                    "width" => 92,
                    "colspan" => 4,
                ],
                [
                    "dataIndex" => "hours",
                    "align" => "right",
                    "width" => 92,
                ],
                [
                    "dataIndex" => "vari_hours",
                    "align" => "right",
                    "width" => 92,
                ],
                [
                    "dataIndex" => "percent_vari_hours",
                    "align" => "right",
                    "width" => 92,
                ],
                [
                    "title" => "Man-hours <br/>(SUM)",
                    "dataIndex" => "target_man_hours",
                    "align" => "right",
                    "width" => 100,
                    "colspan" => 4,

                ],
                [
                    "dataIndex" => "man_hours",
                    "align" => "right",
                    "width" => 100,
                ],
                [
                    "dataIndex" => "vari_man_hours",
                    "align" => "right",
                    "width" => 100,
                ],
                [
                    "dataIndex" => "percent_vari_man_hours",
                    "align" => "right",
                    "width" => 100,
                ],
            ];
    }

    public function tableDataHeader($dataSource, $params)
    {
        return [
            'man_power' => 'Actual',
            'target_man_power' => 'Target',
            'vari_man_power' => 'Variance',
            'percent_vari_man_power' => 'Var.%',

            'hours' => 'Actual',
            'target_hours' => 'Target',
            'vari_hours' => 'Variance',
            'percent_vari_hours' => 'Var.%',

            'man_hours' => 'Actual',
            'target_man_hours' => 'Target',
            'vari_man_hours' => 'Variance',
            'percent_vari_man_hours' => 'Var.%',
        ];
    }

    public function getBasicInfoData($params)
    {
        $projectName = Project::find($params['project_id'] ?? $this->projectId)->name;
        $subProjectName = Sub_project::find($params['sub_project_id'] ?? $this->subProjectId);
        $prodPouting = Prod_routing::find($params['prod_routing_id'] ?? $this->prodRoutingId)->name;

        $prodDiscipline = isset($params['prod_discipline_id']) ?
            Prod_discipline::find($params['prod_discipline_id'])->name:
            ParameterReport::getStringIds('prod_discipline_id', 'name');

        $prodRoutingLink = isset($params['prod_routing_link_id']) ?
            implode(',', Prod_routing_link::whereIn('id', $params['prod_routing_link_id'])
                ->pluck('name')
                ->toArray()) : '';

        $basicInfoData = [];
        $basicInfoData['date'] =  $params['picker_date'];
        $basicInfoData['project'] = $projectName;
        $basicInfoData['sub_project'] = $subProjectName->name;
        $basicInfoData['prod_routing'] = $prodPouting;
        $basicInfoData['prod_routing_link'] = $prodRoutingLink;
        $basicInfoData['prod_discipline'] = $prodDiscipline;
        $basicInfoData['created_at'] = date("d/m/Y", strtotime($subProjectName->created_at->toDateTimeString()));
        return $basicInfoData;
    }

    private function getIcon($value){
        $value = (float)$value;
        if ($value > 0) {
            return '<i class="text-green-600 text-xs fa-solid fa-triangle fa-rotate-180 pr-1"></i>';
        } elseif ($value < 0) {
            return  '<i class="text-red-600 text-xs fa-solid fa-triangle pl-1"></i>';
        } else {
            return '<i class="text-yellow-600 fa-solid fa-minus fa-xl" style="transform: scale(0.7, 1.5); margin-top: 9px; margin-right: -3px" ></i>';
        }
    }

    public function changeDataSource($dataSource, $params)
    {
        foreach ($dataSource as $key => $values) {
            $values = (array)$values;
            $paramUrl = "?project_id={$values['project_id']}";
            $paramUrl .= "&sub_project_id={$values['sub_project_id']}";
            $paramUrl .= "&prod_routing_id={$values['prod_routing_id']}";
            $paramUrl .= "&prod_routing_link_id={$values['prod_routing_link_id']}";
            $paramUrl .= "&picker_date={$params['picker_date']}";
            if (isset($params['prod_discipline_id'])) $paramUrl .= "&prod_discipline_id={$values['prod_discipline_id']}";
            $url = route('report-prod_sequence_030') . $paramUrl;

            // icons
            $iconHours =  $this->getIcon($values['vari_hours']);
            $iconManPower =  $this->getIcon($values['vari_man_power']);
            $iconManHours =  $this->getIcon($values['vari_man_hours']);


            $array = [
                "percent_vari_man_hours" => ["cell_title" => "100 - (({{man_hours}} / {{target_man_hours}})*100)",],
                "percent_vari_man_power" => ["cell_title" => "100 - (({{man_power}} / {{target_man_power}})*100)",],
                "percent_vari_hours" => ["cell_title" => "100 - (({{hours}} / {{target_hours}})*100)",],
                "vari_man_power" => ["cell_title" => "{{target_man_power}} - {{man_power}}",],
                "vari_hours" => ["cell_title" => "{{target_hours}} - {{hours}}",],
                "vari_man_hours" => ["cell_title" => "{{target_man_hours}} - {{man_hours}}",],
                'hours' => ['href' => $url, 'icon' => $iconHours],
                'man_power' => ['href' => $url, 'icon' => $iconManPower],
                'man_hours' => ['href' => $url, 'icon' => $iconManHours],
            ];
            // dd($array);
            $values = ModificationDataReport::addFormulaForData($values, $array);
            $dataSource[$key] = $values;
        }
        // dd($dataSource);
        return $dataSource;
    }
}
