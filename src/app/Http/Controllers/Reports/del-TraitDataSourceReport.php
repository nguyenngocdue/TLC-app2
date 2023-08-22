<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\DB;

trait TraitDataSourceReport
{

    private function getSql($params)
    {
        $sqlStr = $this->getSqlStr($params);
        preg_match_all('/{{([^}]*)}}/', $sqlStr, $matches);
        foreach (last($matches) as $key => $value) {
            if (isset($params[$value])) {
                $valueParam =  $params[$value];
                $searchStr = head($matches)[$key];
                $sqlStr = str_replace($searchStr, $valueParam, $sqlStr);
            }
        }
        return $sqlStr;
    }

    private function overKeyAndValueDataSource($params, $data)
    {
        $dataSource = [];
        foreach ($data as $key => $values) {
            $dataSource[$key]['tableDataSource'] = $values;
            $dataSource[$key]['tableColumns'] = $this->getTableColumns($params, $data)[$key];
        }
        return $dataSource;
    }

    public function getDataSource($params)
    {
        $arraySqlStr = $this->createArraySqlFromSqlStr($params);
        if (empty($arraySqlStr)) {
            $sql = $this->getSql($params);
            if (is_null($sql) || !$sql) return collect();
            $sqlData = DB::select(DB::raw($sql));
            return collect($sqlData);
        }
        $data = [];
        foreach ($arraySqlStr as $k => $sql) {
            if (is_null($sql) || !$sql) return collect();
            $sqlData = DB::select(DB::raw($sql));
            $data[$k] = collect($sqlData);
        }
        $dataSource = $this->overKeyAndValueDataSource($params, $data);
        return $dataSource;
    }
    
    public function getBasicInfoData($params)
    {
        return [];
    }

}
