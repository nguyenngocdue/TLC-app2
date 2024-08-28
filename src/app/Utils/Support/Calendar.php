<?php

namespace App\Utils\Support;

use App\Models\Diginet_employee_leave_line;
use App\Models\Hr_leave_cat;
use App\Models\Hr_leave_line;
use App\Models\Hr_timesheet_officer_line;
use App\Models\Public_holiday;
use Illuminate\Support\Facades\Log;

class Calendar
{
    public static function getBkColorByWorkModeId($workModelId)
    {
        return match ($workModelId * 1) {
            1 => '#14b8a6',
            2 => '#0284c7',
            3 => '#22d3ee',
            default => '#ff0000',
        };
    }

    public static function renderSubTitle($item)
    {
        switch (true) {
            case $item instanceof Public_holiday:
                return $item->name;
            case $item instanceof Diginet_employee_leave_line:
                return $item->la_reason;
            case $item instanceof Hr_timesheet_officer_line:
                if ($item->sub_task_id) return $item->getSubTask->name ?? 'Task Name ???';
            case $item instanceof Hr_leave_line:
                return $item->getLeaveType->name ?? 'Leave Type ???';
            default:
                return ""; // For duplicated sub task and task will eliminate sub task                
        }
    }
    public static function renderTitle($item)
    {
        switch (true) {
            case $item instanceof Public_holiday:
                return "Public Holiday";
            case $item instanceof Diginet_employee_leave_line:
            case $item instanceof Hr_leave_line:
                return "Leave Application";
            default:
                return $item->getTask->name ?? '';
        }
    }
    public static function renderSubProject($item)
    {
        switch (true) {
            case $item instanceof Public_holiday:
                return "PH";
            case $item instanceof Diginet_employee_leave_line:
                return $item->la_type;
            case $item instanceof Hr_leave_line:
                $type = Hr_leave_cat::findFromCache($item->leave_cat_id);
                return $type->leave_code;
            default:
                return  $item->getSubProject->name ?? '';
        }
    }

    public static function renderPhase($item)
    {
        switch (true) {
            case $item instanceof Public_holiday:
            case $item instanceof Diginet_employee_leave_line:
            case $item instanceof Hr_leave_line:
                return;
            default:
                return $item->getLod->name ?? 'PHASE ???';
        }
    }
}
