<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Prod_order;
use App\Models\Qaqc_insp_chklst;
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
    public function getType()
    {
        return "dashboard";
    }



    private function getSql($urlParams)
    {
        // dd($urlParams);
        $sqlStr = $this->getSqlStr($urlParams);
        if (empty($urlParams)) return  dd("<x-feedback.alert type='warning' message='Check URL Parameters'></x-feedback.alert>");
        // if (is_null($urlParams[$x = array_key_first($urlParams)])) return dd("<x-feedback.alert type='warning' message='$x parameter is empty at URL'></x-feedback.alert>");

        preg_match_all('/{{([^}]*)}}/', $sqlStr, $matches);
        foreach (last($matches) as $key => $value) {
            if (isset($urlParams[$value])) {
                $valueParam =  $urlParams[$value];
                $searchStr = head($matches)[$key];
                $sqlStr = str_replace($searchStr, $valueParam, $sqlStr);
            }
        }
        // dump($sqlStr);
        return $sqlStr;
    }

    protected function getDataSource($urlParams)
    {
        $sql = $this->getSql($urlParams);
        $sqlData = DB::select($sql);
        $result = array_map(fn ($item) => (array) $item, $sqlData);
        return $result;
    }

    protected function enrichDataSource($dataSource)
    {
        return $dataSource;
    }


    public function index(Request $request)
    {
        $urlParams = $request->all();

        $currentRoute = CurrentRoute::getTypeController();
        $viewName = strtolower(Str::singular($currentRoute));

        $dataSource = $this->getDataSource($urlParams);
        $dataSource = $this->enrichDataSource($dataSource);
        $columns = $this->getTableColumns($dataSource);
        $prod_orders  = Prod_order::get()->pluck('name', 'id')->toArray();

        $subProjects = Sub_project::get()->pluck('name', 'id')->toArray();
        $chklts_Sheet = Qaqc_insp_chklst::get()->pluck('name', 'id')->toArray();


        // dd($dataSource);
        $sheets = array_map(fn ($item) => array_pop($item)['sheet_name'], $dataSource);


        $typeReport = CurrentRoute::getCurrentController();
        return view('reports.' . $viewName, [
            'tableColumns' => $columns,
            'tableDataSource' => $dataSource,
            'subProjects' => $subProjects,
            'chklts_Sheet' => $chklts_Sheet,
            'prod_orders' => $prod_orders,
            'urlParams' => $urlParams,
            'entity' => $currentRoute,
            'typeReport' => $typeReport,
            'sheets' => $sheets
        ]);
    }
}
