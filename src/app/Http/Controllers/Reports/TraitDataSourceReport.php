<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\DB;

trait TraitDataSourceReport
{

    protected function enrichDataSource($dataSource, $modeParams)
    {
        return $dataSource;
    }

    protected function transformDataSource($dataSource, $modeParams)
    {
        return $dataSource;
    }

    private function getSql($modeParams)
    {
        $sqlStr = $this->getSqlStr($modeParams);
        preg_match_all('/{{([^}]*)}}/', $sqlStr, $matches);
        foreach (last($matches) as $key => $value) {
            if (isset($modeParams[$value])) {
                $valueParam =  $modeParams[$value];
                $searchStr = head($matches)[$key];
                $sqlStr = str_replace($searchStr, $valueParam, $sqlStr);
            }
        }
        return $sqlStr;
    }

    public function getDataSource($modeParams)
    {
        $sql = $this->getSql($modeParams);
        if (is_null($sql) || !$sql) return collect();
        $sqlData = DB::select(DB::raw($sql));
        $collection = collect($sqlData);
        return $collection;
    }

    private function prepareDataRender($modeParams, $data)
    {
        $dataSource = [];
        foreach ($data as $key => $values) {
            $dataSource[$key]['tableDataSource'] = $values;
            $dataSource[$key]['tableColumns'] = $this->getTableColumns($modeParams, $data)[$key];
        }
        return $dataSource;
    }

    public function createArraySqlFromSqlStr($modeParams){
        return [];
    }

    public function getDataSource2($modeParams)
    {
        $arraySqlStr = $this->createArraySqlFromSqlStr($modeParams);
        $data = [];
        foreach ($arraySqlStr as $k => $sql) {
            if (is_null($sql) || !$sql) return collect();
            $sqlData = DB::select(DB::raw($sql));
            $collection = collect($sqlData);
            $data[$k] = $collection;
        }
        $dataSource = $this->prepareDataRender($modeParams, $data);
        return $dataSource;
    }

    public function getBasicInfoData($modeParams)
    {
        return [];
    }

}
