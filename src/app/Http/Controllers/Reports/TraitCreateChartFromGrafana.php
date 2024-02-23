<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;

trait TraitCreateChartFromGrafana
{

    public function createChartFromGrafana($urlCharts, $params)
    {
        $paramsColsFilter = $this->getParamColumns([], $params);
        $strUrlAdvancedFilter = $this->generateStrURlFromAdvancedFilter($paramsColsFilter, $params);
        $result = [];
        foreach ($urlCharts as $item) $result[$item['panelId']] = ['url' =>  $item['base_url'] . '&' . $strUrlAdvancedFilter . '&theme=light' . '&panelId=' . $item['panelId']];
        return $result;
    }
}
