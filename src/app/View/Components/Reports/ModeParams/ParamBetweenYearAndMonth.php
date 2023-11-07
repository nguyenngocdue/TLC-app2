<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;

class ParamBetweenYearAndMonth extends ParentParamReports
{
    protected function getDataSource()
    {
        return [
            [
                'id' => 1,
                'name' => 'Filter by Year'
            ],
            [
                'id' => 2,
                'name' => 'Filter by Month'
            ]
        ];
    }
}