<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Http\Controllers\Workflow\LibReports;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class Eco_sheet_010 extends Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitModeParamsReport;
    use Eco_sheet_dataSource;

    protected $mode = '010';
    protected $maxH = 50;

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

    private function getArraySqlStrs($modeParams)
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
        foreach ($this->getArraySqlStrs($modeParams) as $k => $sql) {
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


    private function getTableColumns()
    {
        return [
            "getEcoLaborImpacts" => [
                [
                    "dataIndex" => "department_name",
                    "align" => "left",

                ],
                [
                    "dataIndex" => "head_count",
                    "align" => "right"

                ],
                [
                    "dataIndex" => "man_day",
                    "align" => "right"

                ],
                [
                    "dataIndex" => "labor_cost",
                    "align" => "right"

                ],
                [
                    "dataIndex" => "total_cost",
                    "align" => "right"

                ],
            ],
            "getEcoSheetsMaterialAdd" => [
                [
                    "dataIndex" => "ecos_name",
                    "align" => "left"
                ],
                [
                    "dataIndex" => "ecos_total_add_cost",
                    "align" => "right"
                ],
            ],
            "getEcoSheetsMaterialRemove" => [
                [
                    "dataIndex" => "ecos_name",
                    "align" => "left"

                ],
                [
                    "dataIndex" => "ecos_total_remove_cost",
                    "align" => "right"

                ],
            ]
        ];
    }

    private function makeTitleForTables()
    {
        $tableName = array_keys($this->getTableColumns());
        $name = ['Labor Impact', 'Material Impact Add', 'Material Impact Remove'];
        return array_combine($tableName, $name);
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
        $dataRender = [];
        foreach ($data as $key => $values) {
            $dataRender[$key]['tableDataSource'] = $values->toArray();
            $dataRender[$key]['tableColumns'] = $this->getTableColumns()[$key];
        }
        return view('reports.document-eco-sheet', [
            'topTitle' => $this->getMenuTitle(),
            'modeReport' => $modeReport,
            'typeReport' => $typeReport,
            'mode' => $this->mode,
            'modeParams' => $modeParams,
            'currentMode' =>  $this->mode,
            'routeName' => $routeName,
            'entity' => $entity,
            'maxH' => $this->maxH,

            'paramColumns' => $this->getParamColumns(),
            'dataRender' => $dataRender,
            'titleTables' => $this->makeTitleForTables(),
        ]);
    }
}
