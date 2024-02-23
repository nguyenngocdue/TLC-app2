<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;

trait TraitParamURLGrafana
{
    abstract protected function getParamColumns($dataSource, $params);
    public function createParamsGrafana($params)
    {
        $paramsUrl = $this->getParamColumns([], []);
        $paramUrlGrafana = ['prams_url_str' =>  $this->generateStrURlFromAdvancedFilter($paramsUrl, $params)];
        return array_merge($params, $paramUrlGrafana);
    }

    private function createStrParamUrl($keyParam, $params)
    {
        $str = '';
        if (isset($params[$keyParam])) {
            foreach ($params[$keyParam] as $value) {
                $str .= '&var-' . $keyParam . "=" . $value . "&";
            }
        }
        return $str;
    }
    private function generateStrURlFromAdvancedFilter($paramsCols, $params)
    {
        $dataIndexes = ($temp = array_column($paramsCols, 'dataIndex')) ? $temp : $paramsCols;
        if (Report::checkValueOfField($params, 'only_month')) {
            $editedMonth = Report::addZeroToValue($params['only_month']);
            $params['only_month'] = $editedMonth;
        }
        $strURL = '';
        foreach ($dataIndexes as $keyParam) {
            if (isset($params[$keyParam]) && $keyParam === 'picker_date') {
                $pickerDate = DateReport::explodePickerDate($params['picker_date']);
                [$from, $to] = DateReport::convertDatesToTimestamps($pickerDate);
                $strTime = 'from=' . $from . '&to=' . $to;
                $strURL .= $strTime;
            } else if (isset($params[$keyParam])) {
                if (is_array($params[$keyParam])) {
                    $formatField = $this->createStrParamUrl($keyParam, $params);
                    $strURL .= $formatField;
                }
                if (isset($params[$keyParam]) && !is_array($params[$keyParam])) {
                    $formatField = '&var-' . $keyParam . '=' . $params[$keyParam] . '&';
                    $strURL .= $formatField;
                }
            } else {
                $formatField = '&var-' . $keyParam . '=All' . '&';
                $strURL .= $formatField;
            }
        }
        $strURL = trim(str_replace('&&', '&', $strURL), '&');
        return $strURL;
    }
}
