<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Utils\Support\DBTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class Report_ParentController2 extends Controller
{
    abstract protected function getSqlStr();
    abstract protected function getTableColumns();
    protected $viewName;
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
                break;
            }
        }
        return $sqlStr;
    }


    private function getDataSource($urlParams)
    {
        $sql = $this->getSql($urlParams);
        $sqlData = DB::select($sql);
        $result = array_map(fn ($item) => (array) $item, $sqlData);
        // dump($result);

        return $result;
    }

    public function index(Request $request)
    {
        $urlParams = $request->all();
        $columns = $this->getTableColumns();

        // dd($columns);

        $dataSource = $this->getDataSource($urlParams);
        // dump($columns, $dataSource);
        // dump($this->viewName);
        return view($this->viewName, [
            'tableColumns' => $columns,
            'tableDataSource' => $dataSource
        ]);
    }
}
