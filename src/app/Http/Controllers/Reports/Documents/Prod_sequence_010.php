<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Models\Prod_discipline;
use App\Models\Prod_routing;
use App\Models\Prod_routing_link;
use App\Models\Project;
use App\Models\Sub_project;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Prod_sequence_010 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;

    protected $mode = '010';
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 62;
    protected $groupByLength = 1;
    protected $pageLimit = 100000;
    protected $groupBy = 'prod_discipline_name';
    protected $viewName = 'document-prod-sequence-010';
    protected $type = 'prod_sequence';
    protected $optionPrint = "landscape";
    protected $tableTrueWidth = true;
    protected $overTableTrueWidth = true;

    // DataSource
    public function getSqlStr($params)
    {
        $projectId =  $params['project_id'] ?? $this->projectId;
        $subProjectId =  $params['sub_project_id'] ?? $this->subProjectId;
        $prodRoutingId =  $params['prod_routing_id'] ?? $this->prodRoutingId;
        $prodRoutingLinkIds = isset($params['prod_routing_link_id']) ? implode(',', $params['prod_routing_link_id']) : [];
        $prodDisciplineIds = isset($params['prod_discipline_id']) ? $params['prod_discipline_id'] : '';
        $prodOrderIds =  isset($params['prod_order_id']) ? implode(',', $params['prod_order_id']) : [];

        $pickerDate = $params['picker_date'];

        $sql = "SELECT 
                    sp.project_id AS project_id
                    ,sp.id AS sub_project_id
                    ,sp.name AS sub_project_name
                    ,prl.id AS prod_routing_link_id
                    ,prl.name AS prod_routing_link_name
                    ,pose.status AS prod_sequence_status
                    ,pd.name AS prod_discipline_name
                    ,po.id AS prod_order_id
                    ,po.name AS prod_order_name
                    ,po.prod_routing_id AS prod_routing_id
                    ,FORMAT(pr.worker_number,2) AS man_power
                    ,FORMAT(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end, pr.start))/ 60/60,2)),2) AS hours
                    ,FORMAT((pr.worker_number)*SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end, pr.start))/ 60/60,2)),2) AS man_hours 
                    
                    FROM sub_projects sp
                    JOIN prod_orders po ON po.sub_project_id = sp.id
                    LEFT JOIN prod_sequences pose ON pose.prod_order_id = po.id AND pose.deleted_by IS NULL
                    JOIN prod_routing_links prl ON prl.id = pose.prod_routing_link_id
                    JOIN prod_runs pr ON pr.prod_sequence_id = pose.id AND pr.deleted_by IS NULL
                    JOIN prod_disciplines pd ON prl.prod_discipline_id = pd.id
                    WHERE 1 = 1
                    AND sp.project_id = $projectId
                    AND sp.id = $subProjectId
                    AND po.prod_routing_id = $prodRoutingId
                    AND pose.status IN ('in_progress', 'finished', 'on_hold')
                    AND po.status IN ('in_progress', 'finished', 'on_hold')
                    AND SUBSTR(pr.date,1,10) = '$pickerDate'";
        if ($prodRoutingLinkIds) $sql .= "\n AND pose.prod_routing_link_id IN ($prodRoutingLinkIds)";
        if ($prodDisciplineIds) $sql .= "\n AND prl.prod_discipline_id = $prodDisciplineIds";
        if ($prodOrderIds) $sql .= "\n AND po.id IN ($prodOrderIds)";

        $sql .= "\n GROUP BY prod_discipline_name, prod_routing_link_id, po.id,pr.worker_number
                    ORDER BY prod_routing_link_name,prod_order_name,prod_discipline_name ";
        // dump($sql);
        return $sql;
    }

    protected function createArraySqlFromSqlStr($params)
    {
        $sqlStr = [];
        $manyParams = $this->createManyParamsFromDates($params);
        foreach ($manyParams as $key => $param) {
            $sqlStr[$key] = $this->getSqlStr($param);
        }
        return $sqlStr;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $pickerDate = Report::createDefaultPickerDate('-1 years');
        $params['picker_date'] = $pickerDate;
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
                'validation' => 'date_format:d/m/Y',
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
                'title' => 'Production Order',
                'dataIndex' => 'prod_order_id',
                'hasListenTo' => true,
                'multiple' => true,
            ],
            [
                'title' => 'Production Discipline',
                'dataIndex' => 'prod_discipline_id',
                'allowClear' => true,
                #'multiple' => true,
            ],
            [
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'allowClear' => true,
                'multiple' => true,
                'hasListenTo' => true,

            ],
        ];
    }

    public function getTableColumns($params, $dataSource)
    {
        $optionLayout = $params['optionPrintLayout'] ?? $this->optionPrint;
        return
            [
                [
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
                    "align" => "left",
                    "width" => 358,
                ],
                [
                    "title" => "Production Order",
                    "dataIndex" => "prod_order_name",
                    "align" => "center",
                    "width" => $optionLayout === 'portrait' ? 90: 150,
                ],
                [
                    "title" => "Status",
                    "dataIndex" => "prod_sequence_status",
                    "align" => "left",
                    "width" => 110,
                ],
                [
                    "title" => "Discipline",
                    "dataIndex" => "prod_discipline_name",
                    "align" => "left",
                    "width" => 220,
                ],
                [
                    "title" => "Man-Power",
                    "dataIndex" => "man_power",
                    "align" => "right",
                    "width" => 35,
                    'footer' => "agg_sum"
                ],
                [
                    "title" => "Hours",
                    "dataIndex" => "hours",
                    "align" => "right",
                    "width" => 35,
                    'footer' => "agg_sum"
                ],
                [
                    "title" => "Man-Hours",
                    "dataIndex" => "man_hours",
                    "align" => "right",
                    "width" => 35,
                    'footer' => "agg_sum"
                ]
            ];
    }

    public function getBasicInfoData($params)
    {
        $projectName = Project::find($params['project_id'] ?? $this->projectId)->name;
        $subProjectName = Sub_project::find($params['sub_project_id'] ?? $this->subProjectId)->name;
        $prodPouting = Prod_routing::find($params['prod_routing_id'] ?? $this->prodRoutingId)->name;

        $prodDiscipline = isset($params['prod_discipline_id']) ?
            implode(', ', Prod_discipline::find($params['prod_discipline_id'])
                ->pluck('name')
                ->toArray()) :
            implode(', ', Prod_discipline::all()
                ->pluck('name')
                ->toArray());

        $prodRoutingLink = isset($params['prod_routing_link_id']) ?
            implode(',', Prod_routing_link::whereIn('id', $params['prod_routing_link_id'])
                ->pluck('name')
                ->toArray()) : '';

        $manyParams = $this->createManyParamsFromDates($params);
        $basicInfoData = [];
        foreach ($manyParams as $key => $value) {
            $array = [];
            $array['date'] = date_create_from_format('Y-m-d', $value['picker_date'])->format('d/m/Y');
            $array['project'] = $projectName;
            $array['sub_project'] = $subProjectName;
            $array['prod_routing'] = $prodPouting;
            $array['prod_routing_link'] = $prodRoutingLink;
            $array['prod_discipline'] = $prodDiscipline;
            $basicInfoData[$key] = $array;
        }
        // dd($manyParams);
        return $basicInfoData;
    }

    public function getDataSource($params)
    {
        $arraySqlStr = $this->createArraySqlFromSqlStr($params);
        $dataSource = [];
        foreach ($arraySqlStr as $k => $sql) {
            if (is_null($sql) || !$sql) return collect();
            // $sql = $this->getSql($params);
            $sqlData = DB::select(DB::raw($sql));
            if (empty($sqlData)) continue;
            $dataSource[$k] = collect($sqlData);
        }
        return $dataSource;
    }
}
