<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Http\Controllers\Workflow\LibReports;
use App\Models\Prod_discipline;
use App\Models\Prod_routing;
use App\Models\Project;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentRoute;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class Prod_sequence_010 extends Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitModeParamsReport;

    protected $mode = '010';
    protected $maxH = 50;
    protected $projectId = 5;
    protected $subProjectId = 21;
    protected $prodRoutingId = 2;


    public function getType()
    {
        return $this->getTable();
    }
    protected function getTable()
    {
        $tableName = CurrentRoute::getCurrentController();
        $tableName = substr($tableName, 0, strrpos($tableName, "_"));
        $tableName = strtolower(Str::plural($tableName));
        return $tableName;
    }
    private function makeModeTitleReport($routeName)
    {
        $lib = LibReports::getAll();
        $title = $lib[$routeName]['title'] ?? 'Empty Title 3';
        return $title;
    }

    // DataSource
    private function getSqlStr($modeParams)
    {
        $projectId =  $modeParams['project_id'] ?? $this->projectId;
        $subProjectId =  $modeParams['sub_project_id'] ?? $this->subProjectId;
        $prodRoutingId =  $modeParams['prod_routing_id'] ?? $this->prodRoutingId;
        $prodRoutingLinkId = $modeParams['prod_routing_link_id'] ?? '';
        $prodDisciplineId = $modeParams['prod_discipline_id'] ?? '';
        $sql = "SELECT 
				    sp.project_id AS project_id
					,sp.id AS sub_project_id
					,sp.name AS sub_project_name
					#,po.id AS po_id
					#,po.name AS po_name
					#,po.status AS po_status
					,prl.id AS prod_routing_link_id
					,prl.name AS prod_routing_link_name
                    ,po.prod_routing_id AS prod_routing_id
					,ROUND(SUM(pose.worker_number),2) AS man_power
					,ROUND(SUM(pose.total_hours),2) AS hours
					,ROUND(SUM(pose.worker_number) * SUM(pose.total_hours),2) AS man_hours
					
				FROM sub_projects sp
				JOIN prod_orders po ON po.sub_project_id = sp.id
				LEFT JOIN prod_sequences pose ON pose.prod_order_id = po.id
				JOIN prod_routing_links prl ON prl.id = pose.prod_routing_link_id
				LEFT JOIN prod_routing_details prd 	ON prl.id = prd.prod_routing_link_id 
													AND prd.prod_routing_id = $prodRoutingId
				WHERE 1 = 1
				AND sp.project_id = $projectId
				AND sp.id = $subProjectId
				AND po.prod_routing_id = $prodRoutingId
				AND pose.status IN ('in_progress', 'finished', 'on_hold')
				AND po.status IN ('in_progress', 'finished', 'on_hold')";
        if ($prodRoutingLinkId) $sql .= "\n AND pose.prod_routing_link_id = $prodRoutingLinkId";
        if ($prodDisciplineId) $sql .= "\n AND prl.prod_discipline_id = $prodDisciplineId";
        $sql .= "\n GROUP BY prod_routing_link_id";
        return $sql;
    }

    private function createArraySqlFromSqlStr($modeParams)
    {
        return [
            'dailySequenceTimesheet' => $this->getSqlStr($modeParams)
        ];
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
                'title' => 'Project',
                'dataIndex' => 'project_id',
            ],
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
                // 'hasListenTo' => true,
            ],
            [
                'title' => 'Production Routing',
                'dataIndex' => 'prod_routing_id',
                // 'hasListenTo' => true,
            ],
            [
                'title' => 'Production Discipline',
                'dataIndex' => 'prod_discipline_id',
                // 'hasListenTo' => true,
            ],
            [
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'hasListenTo' => true,
            ],
        ];
    }


    private function getTableColumns()
    {
        return [
            "dailySequenceTimesheet" => [
                [
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
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
            ]
        ];
    }

    private function makeTitleForTables()
    {
        $tableName = array_keys($this->getTableColumns());
        $name = ['Production Routing Link List'];
        return array_combine($tableName, $name);
    }

    private function getBasicInfoData($modeParams, $data)
    {
        $projectName = Project::find($modeParams['project_id'] ?? 5)->name;
        $data = $data['dailySequenceTimesheet']->toArray();
        $data = reset($data);
        $prod_routing = Prod_routing::find($modeParams['prod_routing_id'] ?? 2)->name;
        $prod_discipline = isset($modeParams['prod_discipline_id']) ? Prod_discipline::find($modeParams['prod_discipline_id'])->name : '';
        return [
            'date' => date('d/m/Y'),
            'project' => $projectName,
            'sub_project' => $data->sub_project_name,
            'prod_routing' => $prod_routing,
            'prod_routing_link' => $data->prod_routing_link_name,
            'prod_discipline' => $prod_discipline,
        ];
    }

    private function changeValue($key, $values)
    {
        return $values;
    }

    public function index(Request $request)
    {
        $typeReport = CurrentPathInfo::getTypeReport($request);
        $routeName = $request->route()->action['as'];
        $modeReport = $this->makeModeTitleReport($routeName);

        $entity = CurrentPathInfo::getEntityReport($request);
        $modeParams = $this->getModeParams($request);

        $input = $request->input();
        if (!$request->input('page') && !empty($input)) {
            return $this->forwardToMode($request, $modeParams);
        }

        $data = $this->getDataSource($modeParams);

        $basicInfoData =  $this->getBasicInfoData($modeParams, $data);
        // dd($basicInfoData);

        $dataRender = [];
        foreach ($data as $key => $values) {
            $values = $this->changeValue($key, $values);
            $dataRender[$key]['tableDataSource'] = $values;
            $dataRender[$key]['tableColumns'] = $this->getTableColumns()[$key];
        }

        return view('reports.document-daily-progress-routing', [
            'topTitle' => $this->getMenuTitle(),
            'modeReport' => $modeReport,
            'typeReport' => $typeReport,
            'mode' => $this->mode,
            'modeParams' => $modeParams,
            'currentMode' =>  $this->mode,
            'routeName' => $routeName,
            'entity' => $entity,
            'maxH' => $this->maxH,

            'basicInfoData' => $basicInfoData,
            'paramColumns' => $this->getParamColumns(),
            'dataRender' => $dataRender,
            'titleTables' => $this->makeTitleForTables(),
        ]);
    }
}
