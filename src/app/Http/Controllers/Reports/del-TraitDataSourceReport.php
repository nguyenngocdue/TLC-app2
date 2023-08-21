<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\DB;

trait TraitDataSourceReport
{

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

    private function overKeyAndValueDataSource($modeParams, $data)
    {
        $dataSource = [];
        foreach ($data as $key => $values) {
            $dataSource[$key]['tableDataSource'] = $values;
            $dataSource[$key]['tableColumns'] = $this->getTableColumns($modeParams, $data)[$key];
        }
        return $dataSource;
    }

    public function getDataSource($modeParams)
    {
        $arraySqlStr = $this->createArraySqlFromSqlStr($modeParams);
        if (empty($arraySqlStr)) {
            $sql = $this->getSql($modeParams);
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
        $dataSource = $this->overKeyAndValueDataSource($modeParams, $data);
        return $dataSource;
    }
    
    public function getBasicInfoData($modeParams)
    {
        return [];
    }

}
