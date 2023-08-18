<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\Report_ParentDocumentController;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Http\Controllers\Workflow\LibReports;
use App\Models\Prod_discipline;
use App\Models\Prod_routing;
use App\Models\Prod_routing_link;
use App\Models\Project;
use App\Models\Sub_project;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Prod_sequence_010 extends Report_ParentDocumentController
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitModeParamsReport;

    protected $mode = '010';
    protected $projectId = 5;
    protected $subProjectId = 21;
    protected $prodRoutingId = 2;
    protected $groupByLength = 1;
    protected $topTitleTable = 'Detail Report';
    protected $groupBy = 'prod_discipline_name';

    private function makeModeTitleReport($routeName)
    {
        $lib = LibReports::getAll();
        $title = $lib[$routeName]['title'] ?? 'Empty Title 3';
        return $title;
    }

    // DataSource
    public function getSqlStr($modeParam)
    {
        $projectId =  $modeParam['project_id'] ?? $this->projectId;
        $subProjectId =  $modeParam['sub_project_id'] ?? $this->subProjectId;
        $prodRoutingId =  $modeParam['prod_routing_id'] ?? $this->prodRoutingId;
        $prodRoutingLinkId = isset($modeParam['prod_routing_link_id']) ? implode(',', $modeParam['prod_routing_link_id']) : [];
        $prodDisciplineId = isset($modeParam['prod_discipline_id']) ? implode(',', $modeParam['prod_discipline_id']) : [];

        $pickerDate = $modeParam['picker_date'];

        $sql = "SELECT 
                    sp.project_id AS project_id
                    ,sp.id AS sub_project_id
                    ,sp.name AS sub_project_name
                    ,prl.id AS prod_routing_link_id
                    ,prl.name AS prod_routing_link_name
                    ,pd.name AS prod_discipline_name
                    ,po.prod_routing_id AS prod_routing_id
                    ,SUM(pr.worker_number) AS man_power
                    ,SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end, pr.start))/ 60/60,2)) AS hours
                    ,SUM(pr.worker_number)*SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end, pr.start))/ 60/60,2)) AS man_hours 
                    
                    FROM sub_projects sp
                    JOIN prod_orders po ON po.sub_project_id = sp.id
                    LEFT JOIN prod_sequences pose ON pose.prod_order_id = po.id
                    JOIN prod_routing_links prl ON prl.id = pose.prod_routing_link_id
                    JOIN prod_runs pr ON pr.prod_sequence_id = pose.id
                    JOIN prod_disciplines pd ON prl.prod_discipline_id = pd.id
                    WHERE 1 = 1
                    AND sp.project_id = $projectId
                    AND sp.id = $subProjectId
                    AND po.prod_routing_id = $prodRoutingId
                    AND pose.status IN ('in_progress', 'finished', 'on_hold')
                    AND po.status IN ('in_progress', 'finished', 'on_hold')
                    AND SUBSTR(pr.date,1,10) = '$pickerDate'";
        if ($prodRoutingLinkId) $sql .= "\n AND pose.prod_routing_link_id IN ($prodRoutingLinkId)";
        if ($prodDisciplineId) $sql .= "\n AND prl.prod_discipline_id IN ($prodDisciplineId)";

        $sql .= "\n GROUP BY prod_discipline_name, prod_routing_link_id
                    ORDER BY prod_discipline_name";
        // dump($sql);
        return $sql;
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $a = 'picker_date';
        $b = 'project_id';
        $c = 'sub_project_id';
        $d = 'prod_routing_id';
        $pickerDate = Report::createDefaultPickerDate();
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$a] = $pickerDate;
            $modeParams[$b] = $this->projectId;
            $modeParams[$c] = $this->subProjectId;
            $modeParams[$d] = $this->prodRoutingId;
        }
        return $modeParams;
    }

    private function createManyParamsFromDates($modeParams)
    {
        $pickerDate = $modeParams['picker_date'];
        $dates = explode("-", $pickerDate);
        [$fromDate, $toDate] = [trim($dates[0]), trim($dates[1])];

        $manyDates = PivotReport::getDatesBetween($fromDate, $toDate);
        $manyDates = array_map(fn ($item) => Report::formatDateString($item), $manyDates);

        $manyModeParams = array_map(function ($item) use ($modeParams) {
            $modeParams['picker_date'] =  $item;
            return $modeParams;
        }, $manyDates);
        return $manyModeParams;
    }

    private function createArraySqlFromSqlStr($modeParams)
    {
        $sqlStr = [];
        $manyModeParams = $this->createManyParamsFromDates($modeParams);
        foreach ($manyModeParams as $key => $modeParam) {
            $sqlStr[$key] = $this->getSqlStr($modeParam);
        }
        return $sqlStr;
    }

    private function getArraySqlStr($modeParams)
    {
        $arraySqlStrs = $this->createArraySqlFromSqlStr($modeParams);
        $array = [];
        foreach ($arraySqlStrs as $k => $sqlStr) {
            preg_match_all('/{{([^}]*)}}/', $sqlStr, $matches);
            foreach (last($matches) as $key => $value) {
                if (isset($modeParams[$value])) {
                    $valueParam =  $modeParams[$value];
                    $searchStr = head($matches)[$key];
                    $sqlStr = str_replace($searchStr, $valueParam, $sqlStr);
                    $array[$k] = $sqlStr;
                }
            }
            $array[$k] = $sqlStr;
        }
        return $array;
    }

    public function getDataSource($modeParams)
    {
        $data = [];
        foreach ($this->getArraySqlStr($modeParams) as $k => $sql) {
            if (is_null($sql) || !$sql) return collect();
            $sqlData = DB::select(DB::raw($sql));
            $collection = collect($sqlData);
            $data[$k] = $collection;
        }
        return $data;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Date',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
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
                'allowClear' => true,
                'multiple' => true,
            ],
            [
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'allowClear' => true,
                'multiple' => true,
            ],
        ];
    }

    public function getTableColumns($modeParams, $dataSource)
    {
        $countTables = array_keys($this->createArraySqlFromSqlStr($modeParams));
        $array = [];
        foreach ($countTables as $key) {
            $array[$key] =
                [
                    [
                        "title" => "Production Routing Link",
                        "dataIndex" => "prod_routing_link_name",
                        "align" => "center",
                        "width" => 160,
                    ],
                    [
                        "title" => "Production Discipline",
                        "dataIndex" => "prod_discipline_name",
                        "align" => "center",
                        "width" => 160,
                    ],
                    [
                        "title" => "Man Power",
                        "dataIndex" => "man_power",
                        "align" => "right",
                        "width" => 30,
                        'footer' => "agg_sum"
                    ],
                    [
                        "title" => "Hours",
                        "dataIndex" => "hours",
                        "align" => "right",
                        "width" => 30,
                        'footer' => "agg_sum"
                    ],
                    [
                        "title" => "Man-Hours",
                        "dataIndex" => "man_hours",
                        "align" => "right",
                        "width" => 30,
                        'footer' => "agg_sum"
                    ]
                ];
        }
        return $array;
    }

    private function makeTitleForTables($modeParams)
    {
        $tableName = array_keys($this->getTableColumns($modeParams, []));
        return array_fill_keys($tableName, $this->topTitleTable);
    }

    private function getBasicInfoData($modeParams)
    {
        $projectName = Project::find($modeParams['project_id'] ?? $this->projectId)->name;
        $subProjectName = Sub_project::find($modeParams['sub_project_id'] ?? $this->subProjectId)->name;
        $prodPouting = Prod_routing::find($modeParams['prod_routing_id'] ?? $this->prodRoutingId)->name;

        $prodDiscipline = isset($modeParams['prod_discipline_id']) ?
            implode(', ', Prod_discipline::whereIn('id', $modeParams['prod_discipline_id'])
                ->pluck('name')
                ->toArray()) :
            implode(', ', Prod_discipline::all()
                ->pluck('name')
                ->toArray());

        $prodRoutingLink = isset($modeParams['prod_routing_link_id']) ?
            implode(',', Prod_routing_link::whereIn('id', $modeParams['prod_routing_link_id'])
                ->pluck('name')
                ->toArray()) : '';

        $manyModeParams = $this->createManyParamsFromDates($modeParams);
        $basicInfoData = [];
        foreach ($manyModeParams as $key => $value) {
            $array = [];
            $array['date'] = date_create_from_format('Y-m-d', $value['picker_date'])->format('d/m/Y');
            $array['project'] = $projectName;
            $array['sub_project'] = $subProjectName;
            $array['prod_routing'] = $prodPouting;
            $array['prod_routing_link'] = $prodRoutingLink;
            $array['prod_discipline'] = $prodDiscipline;
            $basicInfoData[$key] = $array;
        }
        // dd($manyModeParams);
        return $basicInfoData;
    }

    public function index(Request $request)
    {
        $typeReport = CurrentPathInfo::getTypeReport($request);
        $routeName = $request->route()->action['as'];
        $modeReport = $this->makeModeTitleReport($routeName);

        $entity = CurrentPathInfo::getEntityReport($request);
        $modeParams = $this->getModeParams($request);
        $modeParams = $this->getDefaultValueModeParams($modeParams, $request);

        $input = $request->input();
        if (!$request->input('page') && !empty($input)) {
            return $this->forwardToMode($request, $modeParams);
        }
        $data = $this->getDataSource($modeParams);
        $basicInfoData =  $this->getBasicInfoData($modeParams);
        $dataRender = [];
        foreach ($data as $key => $values) {
            // $dataRender[$key]['tableDataSource'] = Report::convertToType($values->toArray());
            $dataRender[$key]['tableDataSource'] = $values;
            $dataRender[$key]['tableColumns'] = $this->getTableColumns($modeParams, $data)[$key];
        }
        // dd($basicInfoData, $dataRender);

        return view('reports.document-daily-prod-routing', [
            'entity' => $entity,
            'routeName' => $routeName,
            'groupBy' => $this->groupBy,
            'modeReport' => $modeReport,
            'typeReport' => $typeReport,
            'modeParams' => $modeParams,
            'currentMode' =>  $this->mode,
            'topTitle' => $this->getMenuTitle(),
            'groupByLength' => $this->groupByLength,

            'basicInfoData' => $basicInfoData,
            'paramColumns' => $this->getParamColumns(),
            'dataRender' => $dataRender,
            'titleTables' => $this->makeTitleForTables($modeParams),
        ]);
    }
}
