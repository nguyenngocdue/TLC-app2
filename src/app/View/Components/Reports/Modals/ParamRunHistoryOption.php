<?php

namespace App\View\Components\Reports\Modals;

use App\View\Components\Reports\ParentTypeParamReport;

class ParamRunHistoryOption extends ParentTypeParamReport
{

    protected function getDataSource()
    {
        $dataSource = [['id' => 0, 'name' => 'View only last run'], ['id' => 1, 'name' => 'View all runs']];
        return $dataSource;
    }
}
