<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentTypeParamReport;

class ParamSequenceMode extends ParentTypeParamReport
{
    protected function getDataSource()
    {
        return [
            ['id' => 1, 'name' => 'Major Sequences'],
            ['id' => 2, 'name' => 'All Sequences'],
        ];
    }
}
