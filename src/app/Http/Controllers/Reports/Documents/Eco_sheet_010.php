<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\Report_ParentDocumentController;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Http\Controllers\Workflow\LibReports;
use App\Models\Project;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class Eco_sheet_010 extends Report_ParentDocumentController
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitModeParamsReport;
    use Eco_sheet_dataSource;

    protected $mode = '010';

    public function getType()
    {
        return $this->getTable();
    }
    public function getSqlStr($modeParams) {
        return $this->createArraySqlFromSqlStr($modeParams);
    }

    protected function getTable()
    {
        $tableName = CurrentRoute::getCurrentController();
        $tableName = substr($tableName, 0, strrpos($tableName, "_"));
        $tableName = strtolower(Str::plural($tableName));
        return $tableName;
    }

    // DataSource

    public function getDataSource($modeParams)
    {
        $data = [];
        foreach ($this->getSqlStr($modeParams) as $k => $sql) {
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
                'title' => 'Month',
                'dataIndex' => 'month',
            ],
            [
                'title' => 'Project',
                'dataIndex' => 'project_id',
            ]
        ];
    }


    public function getTableColumns($modeParams, $dataSource)
    {
        return [
            "getEcoLaborImpacts" => [
                [
                    "title" => "Department",
                    "dataIndex" => "department_name",
                    "align" => "left",
                ],
                [
                    "title" => "Headcounts (Man)",
                    "dataIndex" => "head_count",
                    "align" => "right",
                ],
                [
                    "title" => "Man-day (Day)",
                    "dataIndex" => "man_day",
                    "align" => "right",
                ],
                [
                    "title" => "Labor Cost (USD)",
                    "dataIndex" => "labor_cost",
                    "align" => "right",
                ],
                [
                    "title" => "Total Cost (USD)",
                    "dataIndex" => "total_cost",
                    "align" => "right",
                    // "footer" => "agg_sum"
                ],
            ],
            "getEcoSheetsMaterialAdd" => [
                [
                    "title" => "ECO",
                    "dataIndex" => "ecos_name",
                    "align" => "left"
                ],
                [
                    "title" => "Total Cost",
                    "dataIndex" => "ecos_total_add_cost",
                    "align" => "right"
                ],
            ],
            "getEcoSheetsMaterialRemove" => [
                [
                    "title" => "ECO",
                    "dataIndex" => "ecos_name",
                    "align" => "left"

                ],
                [
                    "title" => "Total Cost",
                    "dataIndex" => "ecos_total_remove_cost",
                    "align" => "right"

                ],
            ],
            "getTimeEcoSheetSignOff" => [
                [
                    "title" => "User to Sign",
                    "dataIndex" => "user_name",
                    "align" => "center"
                ],
                [
                    "title" => "Latency (days)",
                    "dataIndex" => "latency",
                    "align" => "right"
                ]
            ]
        ];
    }

    private function makeTitleForTables($modeParams)
    {
        $tableName = array_keys($this->getTableColumns($modeParams,[]));
        $name = ['Labor Impact', 'Material Impact Add', 'Material Impact Remove', 'Sign Off'];
        return array_combine($tableName, $name);
    }

    private function getBasicInfoData($modeParams)
    {
        $month = $modeParams['month'] ?? date("Y-m");
        $projectName = Project::find($modeParams['project_id'] ?? 5)->name;
        return [
            'month' => $month,
            'project_name' => $projectName
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

        $entity = CurrentPathInfo::getEntityReport($request);
        $modeParams = $this->getModeParams($request);

        $input = $request->input();
        if (!$request->input('page') && !empty($input)) {
            return $this->forwardToMode($request, $modeParams);
        }

        $data = $this->getDataSource($modeParams);

        $basicInfoData =  $this->getBasicInfoData($modeParams);
        // dd($basicInfoData);

        $dataSource = [];
        foreach ($data as $key => $values) {
            $values = $this->changeValue($key, $values);
            $dataSource[$key]['tableDataSource'] = $values;
            $dataSource[$key]['tableColumns'] = $this->getTableColumns($modeParams, [])[$key];
        }
        $titleReport  = $this->makeTitleReport($routeName);
        dd($dataSource);
        return view('reports.document-eco-sheet', [
            'paramColumns' => $this->getParamColumns(),
            'topTitle' => $this->getMenuTitle(),
            'titleReport' => $titleReport,
            'typeReport' => $typeReport,
            'mode' => $this->mode,
            'modeParams' => $modeParams,
            'currentMode' =>  $this->mode,
            'routeName' => $routeName,
            'entity' => $entity,

            'basicInfoData' => $basicInfoData,
            'dataSource' => $dataSource,
            'titleTables' => $this->makeTitleForTables($modeParams),
        ]);
    }
}
