<?php

namespace App\Utils\Support;

use App\Models\Pj_sub_task;
use App\Models\Pj_task;
use App\Models\Sub_project;
use App\Models\Project;
use App\Models\Public_holiday;
use App\Models\User;
use App\Models\Work_mode;
use Illuminate\Support\Facades\Blade;

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
        if ($item instanceof Public_holiday) {
            return "<div class='h-full'><div>"
                . "<div class='font-semibold'>{$item->name}</div>"
                . "</div>"
                . "</div>";
        }
        $nameTask = Pj_task::findOrFail($item->task_id)->name;
        $nameSubTask = '';
        if ($item->sub_task_id) {
            $nameSubTask = Pj_sub_task::findOrFail($item->sub_task_id)->name ?? '';
        }

        $remark = $item->remark ?? '';
        return "<div class='h-full'><div>"
            . "<div class='font-semibold'>{$nameTask}</div>"
            . ($nameSubTask ? "<div>{$nameSubTask}</div>" : "")
            . ($remark ? "<div class='text-sm'>{$remark}</div>" : "")
            . "</div>"
            . "</div>";
    }
    public static function renderTagSubProject($item)
    {
        if ($item instanceof Public_holiday) return;
        if ($item->sub_project_id) {
            $nameSubProject = Sub_project::findOrFail($item->sub_project_id)->name ?? '';
            $tagSubProject = Blade::render("<div class='flex items-end justify-between'><x-renderer.tag class='leading-none'>$nameSubProject</x-renderer.tag></div>");
        }
        return $tagSubProject ?? '';
    }
    public static function renderNameProject($item)
    {
        if ($item instanceof Public_holiday) return;
        if ($item->project_id) {
            $nameProject = Project::findOrFail($item->project_id)->name ?? '';
        }
        return $nameProject ?? '';
    }
}
