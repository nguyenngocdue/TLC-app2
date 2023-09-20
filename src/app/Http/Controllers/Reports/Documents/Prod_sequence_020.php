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
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Prod_sequence_020 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitUpdateBasicInfoDataSource;

    protected $mode = '020';
    protected $projectId = 5;
    protected $subProjectId = 21;
    protected $prodRoutingId = 2;
    protected $groupByLength = 1;
    protected $groupBy = 'prod_discipline_name';
    protected $viewName = 'document-prod-sequence-020';
    protected $type = 'prod_sequence';
    protected $tableTrueWidth = false;
    protected $pageLimit = 1000;

    // DataSource
    public function getSqlStr($params)
    {
        $params = array_filter($params, function ($value) {
            return !(isset($value[0]) &&  $value[0] === null && is_array($value));
        });
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

                        targetTb.target_hours,
                        IF(actualTb.hours, actualTb.hours, NULL) AS hours,
                        round(actualTb.hours - targetTb.target_hours,2) AS vari_hours,
                        round((actualTb.hours / targetTb.target_hours)*100,2) AS percent_vari_hours,
                    
                        targetTb.target_man_hours,
                        IF(actualTb.man_hours, actualTb.man_hours, NULL) AS man_hours,
                        round(actualTb.man_hours - targetTb.target_man_hours,2) AS vari_man_hours,
                        round((actualTb.man_hours / targetTb.target_man_hours)*100,2) AS percent_vari_man_hours,
                    
                        targetTb.target_man_power,
                        IF(actualTb.man_power,actualTb.man_power, null) AS man_power,
                        round(actualTb.man_power - targetTb.target_man_power,2) AS vari_man_power,
                        round((actualTb.man_power / targetTb.target_man_power)*100,2) AS percent_vari_man_power
                    FROM
                        (SELECT
                        project_id, project_name,sub_project_id,prod_routing_link_desc, sub_project_desc, 
                        sub_project_name, prod_routing_id, prod_discipline_id, prod_routing_link_name
                        ,prod_routing_link_id
                        ,SUM(target_hours) AS target_hours
                        ,SUM(target_man_hours) AS target_man_hours
                        ,SUM(target_man_power) AS target_man_power
                        FROM  (SELECT 
                                    sp.project_id,
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
                        LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id
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
                        actualTb.prod_routing_link_id = targetTb.prod_routing_link_id;";
        return $sql;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $a = 'picker_date';
        $b = 'project_id';
        $c = 'sub_project_id';
        $d = 'prod_routing_id';
        if (Report::isNullParams($params)) {
            $params[$a] = date('d/m/Y');
            $params[$b] = $this->projectId;
            $params[$c] = $this->subProjectId;
            $params[$d] = $this->prodRoutingId;
        }
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
                'multiple' => true,
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
                    "width" => 110,
                    'fixed' => 'left'
                ],
                [
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
                    "align" => "left",
                    "width" => 220,
                    'fixed' => 'left'
                ],
                [
                    "title" => "Man-power <br/>(AVG)",
                    "dataIndex" => "target_man_power",
                    "align" => "right",
                    "width" => 60,
                    "colspan" => 4,

                ],
                [
                    "dataIndex" => "man_power",
                    "align" => "right",
                    "width" => 60,
                ],
                [
                    "dataIndex" => "vari_man_power",
                    "align" => "right",
                    "width" => 60,

                ],
                [
                    "dataIndex" => "percent_vari_man_power",
                    "align" => "right",
                    "width" => 60,

                ],

                [
                    "title" => "Hours <br/>(SUM)",
                    "dataIndex" => "target_hours",
                    "align" => "right",
                    "width" => 60,
                    "colspan" => 4,
                ],
                [
                    "dataIndex" => "hours",
                    "align" => "right",
                    "width" => 60,
                ],
                [
                    "dataIndex" => "vari_hours",
                    "align" => "right",
                    "width" => 60,
                ],
                [
                    "dataIndex" => "percent_vari_hours",
                    "align" => "right",
                    "width" => 60,
                ],
                [
                    "title" => "Man-hours <br/>(SUM)",
                    "dataIndex" => "target_man_hours",
                    "align" => "right",
                    "width" => 60,
                    "colspan" => 4,

                ],
                [
                    "dataIndex" => "man_hours",
                    "align" => "right",
                    "width" => 60,
                ],
                [
                    "dataIndex" => "vari_man_hours",
                    "align" => "right",
                    "width" => 60,
                ],
                [
                    "dataIndex" => "percent_vari_man_hours",
                    "align" => "right",
                    "width" => 60,
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
            implode(', ', Prod_discipline::whereIn('id', $params['prod_discipline_id'])
                ->pluck('name')
                ->toArray()) :
            implode(', ', Prod_discipline::all()
                ->pluck('name')
                ->toArray());

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

    public function changeDataSource($dataSource, $params)
    {
        foreach ($dataSource as $key => $values) {
            $paramUrl = "?project_id={$values['project_id']}";
            $paramUrl .= "&sub_project_id={$values['sub_project_id']}";
            $paramUrl .= "&prod_routing_id={$values['prod_routing_id']}";
            $paramUrl .= "&prod_routing_link_id={$values['prod_routing_link_id']}";
            $paramUrl .= "&picker_date={$params['picker_date']}";
            if (isset($params['prod_discipline_id'])) $paramUrl .= "&prod_discipline_id={$values['prod_discipline_id']}";
            $url = route('report-prod_sequence_030').$paramUrl;

            $array = [
                "percent_vari_man_hours" => ["formula" => "({{man_power}} / {{target_man_power}})*100",],
                "percent_vari_man_power" => ["formula" => "({{man_power}} / {{target_man_power}})*100",],
                "percent_vari_hours" => ["formula" => "({{hours}} / {{target_hours}})*100",],
                "vari_man_power" => ["formula" => "{{man_power}} - {{target_man_power}}",],
                "vari_hours" => ["formula" => "{{hours}} - {{target_hours}}",],
                "vari_man_hours" => ["formula" => "{{man_hours}} - {{target_man_hours}}",],
                'hours' => ['href' => $url],
                'man_power' => ['href' => $url],
                'man_hours' => ['href' => $url],
            ];
            $values = ModificationDataReport::addFormulaForData($values, $array);
            $dataSource[$key] = $values;
        }
        // dd($dataSource);
        return $dataSource;
    }
}
