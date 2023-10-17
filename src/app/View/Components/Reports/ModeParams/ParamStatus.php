<?php

namespace App\View\Components\Reports\ModeParams;

use App\Http\Controllers\Workflow\LibStatuses;
use App\View\Components\Reports\ParentParamReports;

class ParamStatus extends ParentParamReports
{
    protected function getDataSource()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $reportName =last(explode('-',last(explode('/', $uri))));
        $plural = substr($reportName, 0, strrpos($reportName, '_'));
        $statuses = LibStatuses::getFor($plural);
        $dataSource = array_values(array_map(fn($item) =>[
            'id' => $item['name'],
            'name' => $item['title'],
        ], $statuses));
        // dd($dataSource);
        return $dataSource;
    }
}
