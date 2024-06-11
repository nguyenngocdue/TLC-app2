<?php

namespace App\Utils\Support;

use App\Models\Diginet_employee_leave_line;
use App\Models\Public_holiday;
use App\Models\Workplace;
use App\Utils\Constant;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

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
    public static function getBackGroundColorByWorkplaceId($workplaceIds, $tailwind = false)
    {
        if (!is_array($workplaceIds)) $workplaceIds = [$workplaceIds];
        $backGroupColor = $tailwind ? "slate" : "#94a3b8";
        $match = Calendar::mapColor($tailwind ? Constant::COLOR_PUBLIC_HOLIDAY : Constant::COLOR_PUBLIC_HOLIDAY2);
        if (count($workplaceIds) > 1) return $backGroupColor;
        return $match[$workplaceIds[0]]['color'] ?? $backGroupColor;
    }
    public static function mapColor(array $colors)
    {
        $match = [];
        $workplaces = Workplace::all()->pluck('name')->toArray();
        for ($i = 0; $i < count($workplaces); $i++) {
            $match[$i + 1] = [
                "name" => $workplaces[$i],
                "color" => $colors[$i]
            ];
        }
        return $match;
    }
    public static function renderTitlePublicHoliday($item)
    {
        $renderWorkplace = "";
        $match = self::mapColor(Constant::COLOR_PUBLIC_HOLIDAY);
        foreach ($item['workplace_id'] as $id) {
            $value = $match[$id] ?? "";
            if (!$value) continue;
            $renderWorkplace .= "<span class='items-center text-black rounded-lg p-1 bg-" . $value["color"] . "-500 text-xs inline-flex justify-center'>" . $value["name"] . "</span>";
        }
        $name = $item['name'] ?? "";
        return "<div class='items-center'>
                    <div>
                        <div class='text-center text-black items-center justify-center align-middle'>
                            <span class='text-sm'>$name</span>
                        </div>
                        <div class='flex gap-1 py-1 justify-center flex-wrap'>
                        $renderWorkplace
                        </div>
                    </div>
                </div>";
    }
    public static function renderSubTitle($item)
    {
        if ($item instanceof Public_holiday) return "";
        $nameSubTask = '';
        if ($item->sub_task_id) {
            // $nameSubTask = Pj_sub_task::findOrFail($item->sub_task_id)->name ?? '';
            $nameSubTask = $item->getSubTask->name ?? '';
        }

        $remark = $item->remark ?? '';
        return "<div>"
            . ($nameSubTask ? "<div class='text-sm'>{$nameSubTask}</div>" : "")
            . ($remark ? "<div class='text-sm'>{$remark}</div>" : "")
            . "</div>";
    }
    public static function renderTitle($item)
    {
        if ($item instanceof Public_holiday) {
            return "<div class='h-full'><div>"
                . "<div class='font-bold text-sm'>Public Holiday</div>"
                . "<div class='font-semibold'>{$item->name}</div>"
                . "</div>"
                . "</div>";
        }
        if ($item instanceof Diginet_employee_leave_line) return "<div>"
            . "<div class='font-bold'>Leave Application</div>"
            . "<div class='font-semibold text-sm'>{$item->la_reason}</div>"
            . "<div class='text-sm'>(LA Type: {$item->la_type})</div>"
            . "</div>";
        // $nameTask = Pj_task::findOrFail($item->task_id)->name;
        $nameTask = $item->getTask->name ?? '';

        return "<div class='h-full'><div>"
            . "<div class='font-semibold text-sm'>{$nameTask}</div>"
            . "</div>"
            . "</div>";
    }
    public static function renderTagSubProject($item)
    {
        if ($item instanceof Public_holiday) return;
        if ($item instanceof Diginet_employee_leave_line) return;
        if ($item->sub_project_id) {
            $nameSubProject = $item->getSubProject->name ?? '';
            // $nameSubProject = Sub_project::findOrFail($item->sub_project_id)->name ?? '';
            $tagSubProject = Blade::render("<div class='flex items-end justify-between'><x-renderer.tag class='leading-none'>$nameSubProject</x-renderer.tag></div>");
        }
        return $tagSubProject ?? '';
    }

    public static function renderTagPhase($item)
    {
        if ($item instanceof Public_holiday) return;
        if ($item instanceof Diginet_employee_leave_line) return;
        if ($item->lod_id) {
            $name = $item->getLod->name ?? '';
            // $name = Sub_project::findOrFail($item->sub_project_id)->name ?? '';
            $tagSubProject = "<div class='italic text-right'>$name</div>";
            // $tagSubProject = Blade::render("<x-renderer.tag class='leading-none'>$name</x-renderer.tag>");
        }
        return $tagSubProject ?? '';
    }
    // public static function renderNameProject($item)
    // {
    //     if ($item instanceof Public_holiday) return;
    //     if ($item instanceof Diginet_employee_leave_line) return;
    //     if ($item->project_id) {
    //         $nameProject = $item->getProject->name ?? '';
    //         // $nameProject = Project::findOrFail($item->project_id)->name ?? '';
    //     }
    //     return $nameProject ?? '';
    // }
}
