<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentTypeParamReport;

class ParamRunHistoryOptionId extends ParentTypeParamReport
{

    protected function getDataSource()
    {
        $dataSource = [['id' => 0, 'name' => 'View only last run'], ['id' => 1, 'name' => 'View all runs']];
        return $dataSource;
    }
}
