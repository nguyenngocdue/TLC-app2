<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;

trait TraitCreateSQL
{
    public function getSql($params)
    {
        if(isset($params['picker_date']) && $x = $params['picker_date']) {
            $params['picker_date'] = DateReport::formatDateString($x);
        } 
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
        // dump($sqlStr);
        return $sqlStr;
    }
}
