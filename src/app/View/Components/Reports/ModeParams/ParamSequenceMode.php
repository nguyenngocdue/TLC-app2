<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;

class ParamSequenceMode extends ParentParamReports
{
    protected function getDataSource()
    {
        return [
            ['id' => 1, 'name' => 'Major Sequences'],
            ['id' => 2, 'name' => 'All Sequences'],
            ['id' => 3, 'name' => 'PPR-MEPF Sequences'],
        ];
    }
}
