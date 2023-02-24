<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
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
        // dd($urlParams);
        $sqlStr = $this->getSqlStr($urlParams);
        // if (empty($urlParams)) return  "";

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
        $page = $_GET['page'] ?? 1;
        $size = $this->pagingSize;
        $paginationData = (new LengthAwarePaginator(
            $collection->forPage($page, $size),
            $collection->count(),
            $size,
            $page
        ))->appends(request()->query());
        return $paginationData;
    }

    protected function enrichDataSource($dataSource, $urlParams)
    {

        return $dataSource;
    }

    protected function getSheets($dataSource) // Override document report
    {
        return [];
    }

    public function index(Request $request)
    {

        $urlParams = $request->all();
        $currentRoute = CurrentRoute::getTypeController();
        $viewName = strtolower(Str::singular($currentRoute));

        $dataSource = $this->getDataSource($urlParams);
        $dataSource = $this->enrichDataSource($dataSource, $urlParams);

        $dataModeControl = $this->getDataForModeControl($this->getDataSource([]));
        // dump($dataSource);
        $columns = $this->getTableColumns($dataSource);
        $sheets = $this->getSheets($dataSource);

        $typeReport = CurrentRoute::getCurrentController();
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
