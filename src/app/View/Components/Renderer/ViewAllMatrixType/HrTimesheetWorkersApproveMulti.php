<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

class HrTimesheetWorkersApproveMulti extends HrTimesheetWorkers
{
    protected $mode = 'checkbox';

    protected $actionBtnList = [
        'exportSCV' => false,
        'printTemplate' => false,
        'approveMulti' => true,
    ];


    protected function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        return $result;
    }
}
