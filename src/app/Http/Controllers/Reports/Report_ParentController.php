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

    protected function getSheets($dataSource)
    {
        dd($dataSource);
        // if (!is_array($dataSource)) return [];
        $sheets = array_map(function ($item) {
            $x = isset(array_pop($item)['sheet_name']);
            return $x ? ["sheet_name" => array_pop($item)['sheet_name']] : '';
        }, $dataSource->items());
        return $sheets;
    }

    public function index(Request $request)
    {

        $urlParams = $request->all();

        $currentRoute = CurrentRoute::getTypeController();
        $viewName = strtolower(Str::singular($currentRoute));

        $dataSource = $this->getDataSource($urlParams);
        $dataSource = $this->enrichDataSource($dataSource, $urlParams);
        // dd($dataSource);

        $dataModeControl = $this->getDataForModeControl($this->getDataSource([]));


        // dump($dataSource);
        $columns = $this->getTableColumns($dataSource);

        $prod_orders  = Prod_order::get()->pluck('name', 'id')->toArray();
        $subProjects = Sub_project::get()->pluck('name', 'id')->toArray();
        $insp_tmpls = Qaqc_insp_tmpl::get()->pluck('name', 'id')->toArray();


        // $sheets = $this->getSheets($dataSource);
        $sheets = [];



        $typeReport = CurrentRoute::getCurrentController();
        return view('reports.' . $viewName, [
            'tableColumns' => $columns,
            'tableDataSource' => $dataSource,
            'prod_orders' => $prod_orders,
            'subProjects' => $subProjects,
            'insp_tmpls' => $insp_tmpls,
            'urlParams' => $urlParams,
            'entity' => $currentRoute,
            'typeReport' => $typeReport,
            'sheets' => $sheets,
            'dataModeControl' => $dataModeControl
        ]);
    }
}
