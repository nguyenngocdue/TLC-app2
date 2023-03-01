<?php

namespace App\Http\Controllers\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Models\Hr_overtime_request_line;
use App\Models\Prod_order;
use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Sub_project;
use App\Utils\Support\CurrentRoute;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

abstract class Report_ParentController extends Controller
{
    use TraitMenuTitle;
    abstract protected function getSqlStr($urlParams);
    abstract protected function getTableColumns($dataSource);
    abstract protected function getDataForModeControl($dataSource);
    protected $pagingSize = 10;
    public function getType()
    {
        return "dashboard";
    }

    private function getSql($urlParams)
    {
        $sqlStr = $this->getSqlStr($urlParams);
        preg_match_all('/{{([^}]*)}}/', $sqlStr, $matches);
        foreach (last($matches) as $key => $value) {
            if (isset($urlParams[$value])) {
                $valueParam =  $urlParams[$value];
                $searchStr = head($matches)[$key];
                $sqlStr = str_replace($searchStr, $valueParam, $sqlStr);
            }
        }
        // dd($sqlStr);
        return $sqlStr;
    }

    protected function getDataSource($urlParams)
    {
        // dd($urlParams);
        // if (empty($urlParams)) return  (object)[];
        $sql = $this->getSql($urlParams);
        $sqlData = DB::select(DB::raw($sql));
        $collection = collect($sqlData);
        return $collection;
    }

    protected function enrichDataSource($dataSource, $urlParams)
    {
        return $dataSource;
    }

    protected function transformDataSource($dataSource, $urlParams)
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

    private function paginateDataSource($dataSource)
    {
        $page = $_GET['page'] ?? 1;
        $size = $this->pagingSize;
        $dataSource = new LengthAwarePaginator($dataSource->forPage($page, $size), $dataSource->count(), $size, $page); //->appends(request()->query();
        return $dataSource;
    }

    public function index(Request $request)
    {
        // dd($request->route());

        $urlParams = $request->all();
        $currentRoute = CurrentRoute::getTypeController();
        $viewName = strtolower(Str::singular($currentRoute));

        $dataSource = $this->getDataSource($urlParams);
        $dataSource = $this->enrichDataSource($dataSource, $urlParams);
        $dataSource = $this->transformDataSource($dataSource, $urlParams);
        $dataSource = $this->paginateDataSource($dataSource);

        $dataModeControl = $this->getDataForModeControl($this->getDataSource([]));
        $columns = $this->getTableColumns($dataSource);
        $sheets = $this->getSheets($dataSource);

        $typeReport = $this->getMenuTitle();
        // dump($dataSource);

        return view('reports.' . $viewName, [
            'tableColumns' => $columns,
            'tableDataSource' => $dataSource,
            'urlParams' => $urlParams,
            'entity' => $currentRoute,
            'typeReport' => $typeReport,
            'sheets' => $sheets,
            'dataModeControl' => $dataModeControl
        ]);
    }
}
