<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Qaqc_insp_chklst;
use App\Models\Sub_project;
use App\Utils\Support\CurrentRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class Report_ParentController extends Controller
{
    abstract protected function getSqlStr();
    abstract protected function getTableColumns($dataSource = []);
    public function getType()
    {
        return "dashboard";
    }



    private function getSql($urlParams)
    {

        $sqlStr = $this->getSqlStr();
        preg_match_all('/{{([^}]*)}}/', $sqlStr, $matches);
        foreach (last($matches) as $key => $value) {
            if (isset($urlParams[$value])) {
                $valueParam =  $urlParams[$value];
                $searchStr = head($matches)[$key];
                $sqlStr = str_replace($searchStr, $valueParam, $sqlStr);
            }
        }
        $count = preg_match_all('/{{([^}]*)}}/', $sqlStr, $matches);
        if ($count) return "";
        return $sqlStr;
    }

    protected function getDataSource($urlParams)
    {
        $sql = $this->getSql($urlParams);
        if (!$sql) return [];
        $sqlData = DB::select($sql);
        $result = array_map(fn ($item) => (array) $item, $sqlData);
        // dump($result);
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
        // dump($currentRoute);
        $viewName = strtolower(Str::singular($currentRoute));

        $dataSource = $this->getDataSource($urlParams);
        $dataSource = $this->enrichDataSource($dataSource);
        $columns = $this->getTableColumns($dataSource);

        $subProjects = Sub_project::get()->pluck('name', 'id')->toArray();
        $chklts_Sheet = Qaqc_insp_chklst::get()->pluck('name', 'id')->toArray();

        return view('reports.' . $viewName, [
            'tableColumns' => $columns,
            'tableDataSource' => $dataSource,
            'subProjects' => $subProjects,
            'chklts_Sheet' => $chklts_Sheet,
            'urlParams' => $urlParams
        ]);
    }
}
