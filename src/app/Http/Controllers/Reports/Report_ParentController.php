<?php

namespace App\Http\Controllers\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UpdateUserSettings;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class Report_ParentController extends Controller
{
    use TraitMenuTitle;
    abstract protected function getSqlStr($modeParams);
    abstract protected function getTableColumns($dataSource);
    abstract protected function getDataForModeControl($dataSource);

    protected $rotate45Width = false;
    protected $groupBy = false;
    protected $mode;

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
        $currentModelName = strtolower(CurrentRoute::getCurrentController());
        return $currentModelName;
    }

    private function paginateDataSource($dataSource, $pageLimit)
    {
        // dd($dataSource);
        $page = $_GET['page'] ?? 1;
        $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $pageLimit), $dataSource->count(), $pageLimit, $page))->appends(request()->query());
        // dd($dataSource, $pageLimit);
        return $dataSource;
    }

    protected function getCurrentMode($typeReport, $entity)
    {
        $typeReport = strtolower($typeReport);
        $settings = CurrentUser::getSettings();
        return $settings[$entity][$typeReport]['user_mode'] ?? 1001;
    }

    protected function getModeParams($typeReport, $entity)
    {
        $currentMode = $this->getCurrentMode($typeReport, $entity);
        // dump(Log::info($currentMode));
        $typeReport = strtolower($typeReport);
        $settings = CurrentUser::getSettings();
        if (!isset($settings[$entity])) return [];
        if (isset($settings[$entity][$typeReport][$currentMode])) {
            $modeParams = $settings[$entity][$typeReport][$currentMode];
            return $modeParams;
        }
        return [];
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

    protected function setDefaultValueModeParams($modeParams)
    {
        return $modeParams;
    }

    protected function getColorLegends()
    {
        return [];
    }

    protected function modeOptions()
    {
        return ['mode_option' => ['mode_010' => 'Model 010', 'mode_020' => 'Model 020']];
    }

    protected function modeColumns()
    {
        return [[
            'dataIndex' => 'mode_option',
            'allowClear' => true,
        ]];
    }


    public function index(Request $request)
    {
        // $this->mode = $mode;


        if ($request->input('mode_option')) {
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }
        if (!$request->input('page') && !empty($request->input())) {
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }

        $typeReport = CurrentRoute::getTypeController();
        $routeName = $request->route()->action['as'];
        $entity = str_replace(' ', '_', strtolower($this->getMenuTitle()));

        $currentUserId = Auth::id();
        $currentMode = $this->getCurrentMode($typeReport, $entity);
        // $currentMode = $mode;
        // dd($currentMode);
        $modeParams = $this->getModeParams($typeReport, $entity);
        $modeParams = $this->setDefaultValueModeParams($modeParams);

        $dataSource = $this->getDataSource($modeParams);

        $dataSource = $this->enrichDataSource($dataSource, $modeParams);
        // dd($dataSource);
        $dataSource = $this->transformDataSource($dataSource, $modeParams);
        // dump(count($dataSource));
        $pageLimit = $this->getPageParam($typeReport, $entity);
        $dataSource = $this->paginateDataSource($dataSource, $pageLimit);
        // dd($dataSource);

        $dataModeControl = $this->getDataForModeControl($this->getDataSource([]));
        $viewName = strtolower(Str::singular($typeReport));

        // dump($modeParams);

        // dd($viewName);

        return view('reports.' . $viewName, [
            'routeName' => $routeName,
            'tableDataSource' => $dataSource,
            'modeParams' => $modeParams,
            'entity' => $entity,
            'typeReport' => $typeReport,
            'dataModeControl' => $dataModeControl,
            'pageLimit' => $pageLimit,
            'groupBy' => $this->groupBy,
            'rotate45Width' => $this->rotate45Width,
            'currentUserId' => $currentUserId,
            'tableColumns' => $this->getTableColumns($dataSource),
            'sheets' =>  $this->getSheets($dataSource),
            'paramColumns' => $this->getParamColumns(),
            'topTitle' => $this->getMenuTitle(),
            'legendColors' => $this->getColorLegends(),
            'modeOptions' => $this->modeOptions(),
            'modeColumns' => $this->modeColumns(),
            'currentMode' => $currentMode,
        ]);
    }
}
