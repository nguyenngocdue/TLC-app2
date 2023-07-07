<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use Carbon\Carbon;

class Workplace extends ModelExtended
{
    protected $fillable = ["name", "description", "standard_working_hour", "standard_start_time", "standard_start_break", "break_duration_in_min", "def_assignee", "slug"];

    protected $table = 'workplaces';
    protected static $statusless = true;

    public static $eloquentParams = [
        "getDefAssignee" => ["belongsTo", User::class, 'def_assignee'],

        "getUsers" => ['hasMany', User::class, 'workplace'],
        "getPublicHolidays" => ["hasMany", Public_holiday::class, 'workplace_id'],
        "getHrOtrs" => ["hasMany", Hr_overtime_request::class, 'workplace_id'],
    ];
    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefAssignee()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPublicHolidays()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getHrOtrs()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getDurationMorningDefault()
    {
        $startTime =  Carbon::parse($this->standard_start_time);
        $startBreakTime =  Carbon::parse($this->standard_start_break);
        return $startBreakTime->diffInMinutes($startTime);
    }
    public function isDifferentDay()
    {
        return $this->getDurationMorningDefault() > $this->standard_working_min;
    }
    public function getDurationMorning()
    {
        $duration = $this->getDurationMorningDefault();
        $startTime =  Carbon::parse($this->standard_start_time);
        $startBreakTime =  Carbon::parse($this->standard_start_break);
        if ($this->isDifferentDay()) {
            $startTime =  Carbon::parse($this->standard_start_time)->subDay();
        }
        $duration = $startBreakTime->diffInMinutes($startTime);
        return $duration;
    }
    public function getDurationAfternoon()
    {
        return ($this->standard_working_min) - ($this->getDurationMorning());
    }
    public function getStandardStartTimeAfternoon()
    {
        $startBreakTime = Carbon::parse($this->standard_start_break);
        $endBreakTime = $startBreakTime->addMinute($this->break_duration_in_min);
        return $endBreakTime->format('H:i:s');
    }

    public function getTotalWorkingHoursOfMonth($month)
    {
        $allWorkers = $this->getUsers->pluck('id');
        $workingHours = User::getTotalWorkingHoursOfMonth($allWorkers, $month);
        $overtimeHours = User::getTotalOvertimeHoursOfMonth($allWorkers, $month);
        $result = [];
        foreach ($allWorkers as $uid) {
            $total = 0;
            if (isset($workingHours[$uid])) {
                $result[$uid]['working_hours'] = $workingHours[$uid]->working_hours;
                $total += $workingHours[$uid]->working_hours;
            }
            if (isset($overtimeHours[$uid])) {
                $result[$uid]['working_hours'] = $overtimeHours[$uid]->ot_hours;
                $total += $overtimeHours[$uid]->ot_hours;
            }
            if ($total) {
                $result[$uid]['total'] = $total;
            }
        }
        return array_sum(array_map(fn ($u) => $u['total'], $result));
        // uasort($result, fn ($a, $b) => - ($a['total'] <=> $b['total']));
        // dump($workingHours);
        return $result;
    }
}
