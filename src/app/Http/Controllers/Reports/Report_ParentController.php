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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

abstract class Report_ParentController extends Controller
{
    abstract protected function getSqlStr($urlParams);
    abstract protected function getTableColumns($dataSource = []);
    abstract protected function getDataForModeControl($dataSource = []);
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
        // if (!$this->getSql($urlParams)) return [];
        $sql = $this->getSql($urlParams);
        $sqlData = DB::select($sql);
        $result = array_map(fn ($item) => (array) $item, $sqlData);
        return $result;
    }

    protected function enrichDataSource($dataSource, $urlParams)
    {
        return $dataSource;
    }

    protected function getSheets($dataSource)
    {
        if (!is_array($dataSource)) return [];
        $sheets = array_map(function ($item) {
            $x = isset(array_pop($item)['sheet_name']);
            return $x ? ["sheet_name" => array_pop($item)['sheet_name']] : '';
        }, $dataSource);
        return $sheets;
    }

    public function index(Request $request)
    {

        $urlParams = $request->all();

        $currentRoute = CurrentRoute::getTypeController();
        $viewName = strtolower(Str::singular($currentRoute));

        $dataSource = $this->getDataSource($urlParams);

        $dataModeControl = $this->getDataForModeControl($this->getDataSource([]));

        // dd($dataModeControl);

        $dataSource = $this->enrichDataSource($dataSource, $urlParams);
        // dump($dataSource);
        $columns = $this->getTableColumns($dataSource);

        $prod_orders  = Prod_order::get()->pluck('name', 'id')->toArray();
        $subProjects = Sub_project::get()->pluck('name', 'id')->toArray();
        $insp_tmpls = Qaqc_insp_tmpl::get()->pluck('name', 'id')->toArray();



        // dump($dataModeControl);
        $sheets = $this->getSheets($dataSource);
        // dump($sheets);
        // dd($sheets);
        // dd($dataSource);


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
