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
            }
        }
        // dd($sqlStr, $matches);
        return $sqlStr;
    }


    private function getDataSource($urlParams)
    {
        $sql = $this->getSql($urlParams);
        $sqlData = DB::select($sql);
        $result = array_map(fn ($item) => (array) $item, $sqlData);

        return $result;
    }

    public function index(Request $request)
    {
        $urlParams = $request->all();
        // dump($urlParams);
        $columns = $this->getTableColumns();
        $dataSource = $this->getDataSource($urlParams);

        // dd($columns);
        $circleIcon = "<i class='fa-thin fa-circle px-2'></i>";
        $checkedIcon = "<i class='fa-solid fa-circle-check px-2'></i>";

        $lines =  [];
        foreach ($dataSource as $item) {
            $lines[$item['line_id']] = $item;
        }

        $id_lineDesc = array_column($dataSource, 'line_description', 'line_id');
        $desc_ids = [];
        foreach ($id_lineDesc as $id => $value) {
            $desc_ids[$value][] = $id;
        }
        $ids_htmls = [];
        foreach ($desc_ids as $ids) {
            $str = '';
            foreach ($ids as $id) {
                $item = $lines[$id];
                if (!is_null($item['c1'])) {
                    $arrayControl = ['c1' => $item['c1'], 'c2' => $item['c2'], 'c3' => $item['c3'], 'c4' => $item['c4']];
                    $s = "";
                    foreach ($arrayControl as $col => $value) {
                        if ($item['control_value_name'] === $item[$col]) {
                            $s .= "<td class ='px-6 py-4'>" . $checkedIcon . $value . "</td>";
                        } else {
                            $s .= "<td class ='px-6 py-4'>" . $circleIcon . $value . "</td>";
                        }
                    };
                    $runDesc = "<td>" . $item['run_desc'] . ":" . "</td>";
                    $runUpdated = "<td class ='px-6 py-4'>" . $item['run_updated'] . "</td>";



                    $str .= "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" .  $runDesc  . $s . $runUpdated . "</tr>";
                }
                $ids_htmls[$id] = "<table class = 'w-full text-sm text-left text-gray-500 dark:text-gray-400'>" . "<tbody>" . $str . "</tbody>" . "</table>";
            }
        }

        $dataRender = [];
        $desc_id = array_column($dataSource, 'line_id', 'line_description');
        foreach ($desc_id as $id) {
            $dataRender[] = $lines[$id] + ['response_type' => $ids_htmls[$id]];
        }

        return view($this->viewName, [
            'tableColumns' => $columns,
            'tableDataSource' => $dataRender
        ]);
    }
}
