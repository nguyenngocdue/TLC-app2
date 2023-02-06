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
        // dd($result);

        return $result;
    }

    public function index(Request $request)
    {
        $urlParams = $request->all();
        $columns = $this->getTableColumns();
        $dataSource = $this->getDataSource($urlParams);

        // dd($columns);
        $circleIcon = "<i class='fa-thin fa-circle'></i>";
        $checkedIcon = "<i class='fa-solid fa-circle-check'></i>";
        $controlItem = [];
        $ids_lineDescriptions = array_column($dataSource, 'line_description', 'line_id');
        $desc_id = array_column($dataSource, 'line_id', 'line_description');

        $lines =  [];
        foreach ($dataSource as $key => $item) {
            $lines[$item['line_id']] = $item;
        }


        $idsDecs = [];
        foreach ($ids_lineDescriptions as $id => $value) {
            $idsDecs[$value][] = $id;
        }

        $arrayHtmlRender = [];
        $arr = [];
        foreach ($idsDecs as $dec => $ids) {
            $str = '';
            foreach ($ids as $id) {
                $item = $lines[$id];
                if ($item['c1'] !== null) {
                    $arrayControl = ['c1' => $item['c1'], 'c2' => $item['c2'], 'c3' => $item['c3'], 'c4' => $item['c4']];
                    foreach ($arrayControl as $col => $value) {
                        if ($item['control_value_name'] === $item[$col]) {
                            // dump($str . $checkedIcon . $value);
                            $str =    $str . $checkedIcon . $value;
                        } else {
                            $str =  $str . $circleIcon . $value;
                        }
                    }
                }
                $arr[$id] = $str;
                $str = '';
            }
        }

        foreach ($arr as $id => $value) {
            if ($value !== "") {
                $arrayHtmlRender[$id] =   $value;
            } else {
                $arrayHtmlRender[$id] = "";
            }
        }

        $dataRender = [];
        foreach ($desc_id as $key => $id) {
            $dataRender[] = $lines[$id] + ['c5' =>  $arrayHtmlRender[$id]];
        }

        // dd($dataRender);

        // dump($this->viewName);
        return view($this->viewName, [
            'tableColumns' => $columns,
            'tableDataSource' => $dataRender
        ]);
    }
}
