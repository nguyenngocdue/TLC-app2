<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;
use App\Utils\Support\StringReport;

trait TraitCreateChartFromGrafana
{

    private static function generateStrURlFromAdvancedFilter($paramsCols, $params){
        $dataIndexes = array_column($paramsCols, 'dataIndex');
        $strURL = '';
        foreach($dataIndexes as $keyParam) {
            if (isset($params[$keyParam]) && $keyParam === 'picker_date'){
                $pickerDate = DateReport::explodePickerDate($params['picker_date']);
                [$from, $to] = DateReport::convertDatesToTimestamps($pickerDate);
                $strTime = 'from='.$from.'&to='.$to;
                $strURL .= $strTime;
            }
            else if(isset($params[$keyParam])){
                if(is_array($params[$keyParam])){
                    $formatField = StringReport::createStrParamUrl($keyParam, $params);
                    $strURL .= $formatField;
                }
                if(isset($params[$keyParam]) && !is_array($params[$keyParam])) {
                    $formatField = '&var-'.$keyParam .'='.$params[$keyParam];
                    $strURL .= $formatField;
                }
            } else{
                $formatField = '&var-'.$keyParam .'=All';
                    $strURL .= $formatField;
            }
        }
        return $strURL;
    }

    public function createChartFromGrafana($urlCharts, $params){
        $paramsColsFilter = $this->getParamColumns([], $params);
        $strUrlAdvancedFilter = $this->generateStrURlFromAdvancedFilter($paramsColsFilter, $params);
        $result = [];
        foreach ($urlCharts as $item) $result[$item['panelId']] =[ 'url' =>  $item['base_url'].'&'.$strUrlAdvancedFilter .'&theme=light'.'&panelId='.$item['panelId']];
        return $result;
    }
}
