<?php

namespace App\Utils\Support;

use App\Models\Pj_sub_task;
use App\Models\Pj_task;
use App\Models\Work_mode;

class Calendar
{
    public static function setColorByWorkModeId($workModelId)
    {
        return match ($workModelId) {
            1 => '#14b8a6',
            2 => '#0284c7',
            3 => '#22d3ee',
        };
    }
    public static function renderTitle($item)
    {
        $nameTask = Pj_task::findOrFail($item->task_id)->name;
        $nameSubTask = '';
        if ($item->sub_task_id) {
            $nameSubTask = 'Sub task: ' . (Pj_sub_task::findOrFail($item->sub_task_id)->name ?? '');
        }
        $nameWorkMode = '';
        if ($item->work_mode_id) {
            $nameWorkMode = 'Work mode: ' . (Work_mode::findOrFail($item->work_mode_id)->name ?? '');
        }
        $remark = $item->remark ? 'Remark: ' . $item->remark : '';
        return "<div class=''>
                    <div>{$nameTask}</div>
                    <div>{$nameSubTask}</div>
                    <div>{$nameWorkMode}</div>
                    <div>{$remark}</div>
                </div>";
    }
}
