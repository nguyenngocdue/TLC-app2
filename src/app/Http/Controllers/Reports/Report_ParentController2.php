<?php

namespace App\Http\Controllers\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Workflow\LibReports;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DocumentReport;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class Report_ParentController2 extends Controller
{
    use TraitMenuTitle;
    use TraitModeParamsReport;
    use TraitFunctionsReport;
    use TraitLibPivotTableDataFields2;

    protected $mode = '010';
    protected $maxH = null;
    protected $tableTrueWidth = false;
    protected $pageLimit = 10;
    protected $typeView = '';
    protected $modeType = '';
    protected $rotate45Width = false;
    protected $viewName = '';


    // abstract protected function getSqlStr($modeParams);
    public function getType()
    {
        return $this->getTable();
    }

    private function getSql($modeParams)
    {

        $sqlStr = $this->getSqlStr($modeParams);
        preg_match_all('/{{([^}]*)}}/', $sqlStr, $matches);
        foreach (last($matches) as $key => $value) {
            if (isset($modeParams[$value])) {
                $valueParam =  $modeParams[$value];
                $searchStr = head($matches)[$key];
                $sqlStr = str_replace($searchStr, $valueParam, $sqlStr);
            }
        }
        // dd($sqlStr);
        return $sqlStr;
    }

    private function overKeyAndValueDataSource($modeParams, $data)
    {
        $dataSource = [];
        foreach ($data as $key => $values) {
            $dataSource[$key]['tableDataSource'] = $values;
            $dataSource[$key]['tableColumns'] = $this->getTableColumns($modeParams, $data)[$key];
        }
        return $dataSource;
    }

    public function getDataSource($modeParams)
    {
        $arraySqlStr = $this->createArraySqlFromSqlStr($modeParams);
        if (empty($arraySqlStr)) {
            $sql = $this->getSql($modeParams);
            if (is_null($sql) || !$sql) return collect();
            $sqlData = DB::select(DB::raw($sql));
            return collect($sqlData);
        }
        $data = [];
        foreach ($arraySqlStr as $k => $sql) {
            if (is_null($sql) || !$sql) return collect();
            // $sql = $this->getSql($modeParams);
            $sqlData = DB::select(DB::raw($sql));
            $data[$k] = collect($sqlData);
        }
        $dataSource = $this->overKeyAndValueDataSource($modeParams, $data);
        return $dataSource;
    }

    protected function getDefaultValueModeParams($modeParams)
    {
        $x = 'picker_date';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = PivotReport::defaultPickerDate();
        }
        return $modeParams;
    }

    protected function getTable()
    {
        $tableName = CurrentRoute::getCurrentController();
        $tableName = substr($tableName, 0, strrpos($tableName, "_"));
        $tableName = strtolower(Str::plural($tableName));
        return $tableName;
    }


    protected function getPageParam($typeReport, $entity)
    {
        $settings = CurrentUser::getSettings();
        if (!isset($settings[$entity])) return 10;
        if (isset($settings[$entity][strtolower($typeReport)]['per_page'])) {
            $pageLimit = $settings[$entity][strtolower($typeReport)]['per_page'];
            return $pageLimit;
        }
        return 10;
    }

    protected function forwardToMode($request, $modeParams)
    {
        $input = $request->input();
        $isFormType = isset($input['form_type']);
        if ($isFormType && $input['form_type'] === 'updateParamsReport' || $isFormType && $input['form_type'] === 'updatePerPageReport') {
            (new UpdateUserSettings())($request);
        }
        return redirect($request->getPathInfo());
    }

    protected function tableDataHeader($modeParams, $data)
    {
        return [];
    }

    private function makeModeTitleReport($routeName)
    {
        $lib = LibReports::getAll();
        $title = $lib[$routeName]['title'] ?? 'Empty Title 2';
        return $title;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        if (!$modeType) return [[]];
        $filters = $this->getDataFields($modeType)['filters'];
        $colParams = [];
        foreach ($filters as $key => $values) {
            $dataIndex = trim($key);
            $multiple = false;
            if (isset($values->multiple)) {
                if ($values->multiple == 'true' || $values->multiple) $multiple = true;
            }
            $a = [];
            if ($dataIndex === 'picker_date') {
                $a = ['renderer' => 'picker_date'];
            }
            $colParams[] = [
                'title' => $values->title ?? ucwords(str_replace('_', ' ', $key)),
                'allowClear' => $values->allowClear ?? false,
                'multiple' => $multiple,
                'dataIndex' => trim($dataIndex),
                'hasListenTo' => $values->hasListenTo ?? false,
            ] + $a;
        }
        // dd($colParams);
        return $colParams;
    }

    protected function createArraySqlFromSqlStr($modeParams)
    {
        return [];
    }

    protected function getBasicInfoData($modeParams)
    {
        return [];
    }

    public function makeTitleForTables($modeParams)
    {
        return [];
    }

    public function selectMonth($modeParams)
    {
        $month = DocumentReport::getCurrentMonthYear();
        $projectId = 5;
        if (isset($modeParams['month'])) {
            $month = $modeParams['month'];
        }
        if (isset($modeParams['project_id'])) {
            $projectId = $modeParams['project_id'];
        }
        return [$month, $projectId];
    }

    public function index(Request $request)
    {
        $input = $request->input();
        $typeReport = CurrentPathInfo::getTypeReport($request);
        $routeName = $request->route()->action['as'];
        $entity = CurrentPathInfo::getEntityReport($request);
        $modeParams = $this->getModeParams($request);
        $modeParams = $this->getDefaultValueModeParams($modeParams);

        if (!$request->input('page') && !empty($input)) {
            return $this->forwardToMode($request, $modeParams);
        }
        $pageLimit = $this->getPageParam($typeReport, $entity);


        $viewName =  CurrentPathInfo::getViewName($request);
        if ($this->viewName) $viewName = $this->viewName;

        $dataSource = $this->getDataSource($modeParams);
        $tableColumns = $this->getTableColumns($modeParams, $dataSource);
        $tableDataHeader = $this->tableDataHeader($modeParams, $dataSource);
        echo $this->getJS();
        $titleReport = $this->makeModeTitleReport($routeName);
        $modeType = $this->modeType;
        $paramColumns = $this->getParamColumns($dataSource, $modeType);

        //data to render for document reports
        [$dataRenderDocReport , $basicInfoData] = [[], []];
        if (str_contains($routeName, 'document-')) {
            $basicInfoData =  $this->getBasicInfoData($modeParams);
            $dataRenderDocReport = [
                'basicInfoData' => $basicInfoData,
                'titleTables' => $this->makeTitleForTables($modeParams)
            ];
        }

        return view('reports.' . $viewName, [
            'entity' => $entity,
            'maxH' => $this->maxH,
            'mode' => $this->mode,
            'pageLimit' => $pageLimit,
            'routeName' => $routeName,
            'titleReport' => $titleReport,
            'modeParams' => $modeParams,
            'typeReport' => $typeReport,
            'modeType' => $this->modeType,
            'currentMode' =>  $this->mode,
            'typeOfView' => $this->typeView,
            'tableColumns' => $tableColumns,
            'paramColumns' => $paramColumns,
            'tableDataSource' => $dataSource,
            'tableDataHeader' => $tableDataHeader,
            'tableTrueWidth' => $this->tableTrueWidth,
            'rotate45Width' => $this->rotate45Width,
            'topTitle' => $this->getMenuTitle(),
        ] + $dataRenderDocReport);
    }

    private function triggerDataFollowManagePivot($linesData, $modeType, $modeParams)
    {
        $fn = (new DataPivotTable2);
        $data = $fn->makeDataPivotTable($linesData, $modeType, $modeParams);
        return $data;
    }

    public function exportCSV(Request $request)
    {
        $entity = CurrentPathInfo::getEntityReport($request, '_ep');
        $modeParams = $this->getModeParams($request, '_ep');
        $linesData = $this->getDataSource($modeParams);
        $modeType = $this->modeType;
        // Pivot data before render 

        if ($modeType) {
            [$dataOutput, $tableColumns,] = $this->triggerDataFollowManagePivot($linesData, $modeType, $modeParams);
            [$columnKeys, $columnNames] = $this->makeColumnsPivotTable($dataOutput, $modeParams, $tableColumns);
        } else {
            [$columnKeys, $columnNames] = $this->makeColumns($linesData, $modeParams);
        }

        $rows = $this->makeRowsFollowColumns($dataOutput, $columnKeys);
        $fileName = $entity . '_' . date('d:m:Y H:i:s') . '.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $callback = function () use ($rows, $columnKeys, $columnNames) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columnNames);
            $array = [];
            foreach ($rows as $row) {
                foreach ($columnKeys as $key) {
                    $array[$key] = $row[$key] ?? '';
                }
                fputcsv($file, $array);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    protected function getJS()
    {
        return "";
    }
}
