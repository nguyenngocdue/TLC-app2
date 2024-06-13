<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Support\Facades\Blade;

class HrTimesheetWorkersApproveMulti extends HrTimesheetWorkers
{
    protected $mode = 'checkbox_change_status';
    protected $checkboxCaptionColumn = "total_hours";

    protected $actionBtnList = [
        'exportSCV' => false,
        'printTemplate' => false,
        'approveMulti' => true,
    ];


    // protected function getXAxis2ndHeader($xAxis)
    // {
    //     $result = [];
    //     return $result;
    // }

    protected function getMetaColumns()
    {
        return [
            ...parent::getMetaColumns(),
            ['dataIndex' => 'toggle', 'width' => 30, 'align' => "center",],
        ];
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey)
    {
        $parent = parent::getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey);
        $yId = $y->id;
        $route = route($this->type . ".changeStatusMultiple");
        $script = "<script>const route_$yId='$route';</script>";
        $btn = "$script<x-renderer.button size='xs' onClick='toggleCheckbox($yId, route_$yId)'>TG</x-renderer.button>";
        $toggleBtn = Blade::render($btn);
        return array_merge(
            $parent,
            [
                'toggle' => $toggleBtn,
            ]
        );
    }

    function getCheckboxVisibleFromTransition($document, $y)
    {
        $sp = SuperProps::getFor($this->type);
        $status = $document->status;
        $transitions = $sp['statuses'][$status]['transitions'];
        foreach ($transitions as $nextStatus) {
            if ($sp['statuses'][$nextStatus]['action-buttons']['change_status_multiple'] == 'true') {
                return true;
            }
        }
        return false;
    }

    function getCheckboxVisible($document, $y)
    {
        $managerId = CurrentUser::id();
        $creatorId = $document->owner_id;
        $isApprovable = BuildTree::isApprovable($managerId, $creatorId) ? 1 : 0;
        // echo "($managerId-$creatorId-$result)";
        $isTransitionable = $this->getCheckboxVisibleFromTransition($document, $y);
        return $isApprovable && $isTransitionable;
    }
}
