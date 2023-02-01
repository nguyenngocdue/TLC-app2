<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    protected function getSqlStr()
    {
        $sqlStr = "SELECT po.id AS po_id ,ps.id AS sq_id ,
        MIN(CONCAT(pr.date,' ', pr.start)) AS start_datetime,
        MAX(CONCAT(pr.date,' ', pr.end)) AS end_datetime,
        COUNT(DISTINCT pr.id) AS run_id_count
        ,SUM(pur_count) AS user_id_count 
        ,AVG(pur_count) AS average_person
        # ,GROUP_CONCAT(users) as user_id
        FROM prod_orders po 
            JOIN prod_sequences ps ON po.id = ps.prod_order_id
            JOIN prod_runs pr ON pr.prod_sequence_id = ps.id
            # group [pr : pur_count]
            JOIN (SELECT prod_run_id, COUNT(DISTINCT user_id) AS pur_count FROM prod_user_runs GROUP BY prod_run_id) pr_and_user_count ON pr_and_user_count.prod_run_id = pr.id
            # JOIN (SELECT prod_run_id, GROUP_CONCAT(DISTINCT user_id) AS users FROM prod_user_runs GROUP BY prod_run_id) pr_and_user_list ON pr_and_user_list.prod_run_id = pr.id
        WHERE po.id = {{yyy}}
        GROUP BY sq_id";

        return $sqlStr;
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

    protected function getColumns($urlParams)
    {
        $sql = $this->getSql($urlParams);
        $sqlData = DB::select($sql);
        $columnName = array_keys((array)$sqlData[0]);
        $tableColumns = array_map(fn ($item) => ['title' => Str::headline($item), 'dataIndex' => $item], $columnName);
        // dump($tableColumns);
        return $tableColumns;
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
        $columns = $this->getColumns($urlParams);
        $dataSource = $this->getDataSource($urlParams);
        dump($columns, $dataSource);
        return view("welcome-due", [
            'tableColumns' => $columns,
            'tableDataSource' => $dataSource
        ]);
    }
}
