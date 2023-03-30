<?php

namespace App\Http\Controllers\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UpdateUserSettings;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class Report_ParentController extends Controller
{
    use TraitMenuTitle;
    use TraitModeParamsReport;
    abstract protected function getSqlStr($modeParams);
    abstract protected function getTableColumns($dataSource, $modeParams);
    abstract protected function getDataForModeControl($dataSource);

    protected $rotate45Width = false;
    protected $groupBy = false;
    protected $mode = '010';
    protected $groupByLength = 7;

    public function getType()
    {
        return str_replace(' ', '_', strtolower($this->getMenuTitle()));
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

    protected function getDataSource($modeParams)
    {
        $sql = $this->getSql($modeParams);
        $sqlData = DB::select(DB::raw($sql));
        $collection = collect($sqlData);
        return $collection;
    }

    protected function enrichDataSource($dataSource, $modeParams)
    {
        // dd($dataSource);
        return $dataSource;
    }

    protected function getParamColumns()
    {
        return [[]];
    }

    protected function transformDataSource($dataSource, $modeParams)
    {
        return $dataSource;
    }

    protected function getSheets($dataSource) // Override document report
    {
        return [];
    }
    protected function getTable()
    {
        $tableName = CurrentRoute::getCurrentController();
        $tableName = substr($tableName, 0, strrpos($tableName, "_"));
        $tableName = strtolower(Str::plural($tableName));
        return $tableName;
    }

    private function paginateDataSource($dataSource, $pageLimit)
    {
        $page = $_GET['page'] ?? 1;
        $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $pageLimit), $dataSource->count(), $pageLimit, $page))->appends(request()->query());
        return $dataSource;
    }

    protected function getPageParam($typeReport, $entity)
    {
        $settings = CurrentUser::getSettings();
        if (!isset($settings[$entity])) return 10;
        if (isset($settings[$entity][strtolower($typeReport)]['page_limit'])) {
            $pageLimit = $settings[$entity][strtolower($typeReport)]['page_limit'];
            return $pageLimit;
        }
        return 10;
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        return $modeParams;
    }

    protected function getColorLegends()
    {
        return [];
    }

    protected function modeColumns()
    {
        return [
            'title' => 'Mode',
            'dataIndex' => 'mode_option',
            'allowClear' => true
        ];
    }

    protected function forwardToMode($request)
    {
        $input = $request->input();
        $isFormType = isset($input['form_type']);
        if ($isFormType && $input['form_type'] === 'updateParamsReport' || $isFormType && $input['form_type'] === 'updatePerPageReport') {
            (new UpdateUserSettings())($request);
        }
        return redirect($request->getPathInfo());
    }

    public function index(Request $request)
    {

        $input = $request->input();
        // dd($input);
        Log::info($input);

        $typeReport = CurrentPathInfo::getTypeReport($request);
        $routeName = $request->route()->action['as'];
        $entity = str_replace(' ', '_', strtolower($this->getMenuTitle()));

        if (!$request->input('page') && !empty($input)) {
            return $this->forwardToMode($request);
        }

        $currentUserId = Auth::id();
        $currentMode = $this->mode;

        $modeParams = $this->getModeParams($request);
        // dd($modeParams);
        $modeParams = $this->getDefaultValueModeParams($modeParams, $request);

        $dataSource = $this->getDataSource($modeParams);

        $dataSource = $this->enrichDataSource($dataSource, $modeParams);
        $start = microtime(true);
        $dataSource = $this->transformDataSource($dataSource, $modeParams);
        // dump(count($dataSource));

        $sheet = $this->getSheets($dataSource);
        $pageLimit = $this->getPageParam($typeReport, $entity);
        $dataSource = $this->paginateDataSource($dataSource, $pageLimit);
        // dd($dataSource);
        // Execute the query
        $time = microtime(true) - $start;
        // dump($time);
        $dataModeControl = $this->getDataForModeControl($this->getDataSource([]));
        $viewName = CurrentPathInfo::getViewName($request);

        $tableColumns = $this->getTableColumns($dataSource, $modeParams);
        // dd($dataModeControl);

        return view('reports.' . $viewName, [
            'entity' => $entity,
            'sheets' =>  $sheet,
            'pageLimit' => $pageLimit,
            'routeName' => $routeName,
            'modeParams' => $modeParams,
            'groupBy' => $this->groupBy,
            'typeReport' => $typeReport,
            'currentMode' => $currentMode,
            'tableColumns' => $tableColumns,
            'tableDataSource' => $dataSource,
            'currentUserId' => $currentUserId,
            'dataModeControl' => $dataModeControl,
            'rotate45Width' => $this->rotate45Width,
            'groupByLength' => $this->groupByLength,
            'topTitle' => $this->getMenuTitle(),
            'modeColumns' => $this->modeColumns(),
            'modeOptions' => $this->getDataModes(),
            'paramColumns' => $this->getParamColumns(),
            'legendColors' => $this->getColorLegends(),

        ]);
    }
}
