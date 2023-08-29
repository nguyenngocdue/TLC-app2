<?php

namespace App\Http\Controllers\Reports;


trait TraitCreateSQL
{
    public function getSql($params)
    {
        $sqlStr = $this->getSqlStr($params);
        preg_match_all('/{{([^}]*)}}/', $sqlStr, $matches);
        foreach (last($matches) as $key => $value) {
            if (isset($params[$value])) {
                $valueParam =  $params[$value];
                if(is_array($valueParam)) $valueParam = implode(',',$valueParam);
                $searchStr = head($matches)[$key];
                $sqlStr = str_replace($searchStr, $valueParam, $sqlStr);
            }
        }
        return $sqlStr;
    }
}
