<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use Illuminate\Support\Facades\Blade;

class HrTimesheetWorkersApproveMulti extends HrTimesheetWorkers
{
    protected $mode = 'checkbox';
    protected $checkboxCaptionColumn = "total_hours";

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

    protected function getMetaColumns()
    {
        return [
            ...parent::getMetaColumns(),
            ['dataIndex' => 'toggle', 'width' => 30, 'align' => "center",],
        ];
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel)
    {
        $parent = parent::getMetaObjects($y, $dataSource, $xAxis, $forExcel);
        $yId = $y->id;
        $toggleBtn = Blade::render("<x-renderer.button size='xs' onClick='toggleCheckbox($yId)'>TG</x-renderer.button>");
        return array_merge(
            $parent,
            [
                'toggle' => $toggleBtn,
            ]
        );
    }
}
