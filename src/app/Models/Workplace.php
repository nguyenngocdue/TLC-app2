<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use Carbon\Carbon;

class Workplace extends ModelExtended
{
    protected $fillable = [
        "name", "description",
        "standard_working_hour", "standard_start_time", "standard_start_break",
        "break_duration_in_min", "def_assignee", "slug",
        "travel_place_id", "weekend_days",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getDefAssignee" => ["belongsTo", User::class, 'def_assignee'],

        "getUsers" => ['hasMany', User::class, 'workplace'],
        "getPublicHolidays" => ["hasMany", Public_holiday::class, 'workplace_id'],

        "getTravelPlace" => ["belongsTo", Act_travel_place::class, "travel_place_id"],
        // "getHrOtrs" => ["hasMany", Hr_overtime_request::class, 'workplace_id'],

        "getMonitors1" => ["belongsToMany", User::class, "ym2m_user_workplace_monitor_1"],
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
    public function getTravelPlace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPublicHolidays()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    // public function getHrOtrs()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

    public function getMonitors1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
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

    public function getTotalWorkingHoursOfYear($year)
    {
        $HR_WORK_HOURS = 8;
        $allSheets = Esg_master_sheet::query()
            ->where("workplace_id", $this->id)
            ->where('esg_tmpl_id', $HR_WORK_HOURS)
            ->whereYear('esg_month', $year)
            ->get();
        $result = [];
        foreach ($allSheets as $value) $result[substr($value->esg_month, 0, 7)] = $value->total;
        return $result;
    }

    // public function getTotalWorkingHoursOfYearFromTSW($year)
    // {
    //     $allWorkers = $this->getUsers->pluck('id');
    //     $workingHours = User::getTotalWorkingHoursOfYear($allWorkers, $year);
    //     $overtimeHours = User::getTotalOvertimeHoursOfYear($allWorkers, $year);

    //     $result0 = [];
    //     $months = [
    //         "$year-01", "$year-02", "$year-03", "$year-04", "$year-05", "$year-06",
    //         "$year-07", "$year-08", "$year-09", "$year-10", "$year-11", "$year-12"
    //     ];
    //     foreach ($months as $month) {
    //         foreach ($allWorkers as $uid) {
    //             $total = 0;
    //             $key = $uid . "_" . $month;
    //             if (isset($workingHours[$key])) {
    //                 $result0[$key]['working_hours'] = $workingHours[$key]->working_hours;
    //                 $result0[$key]['month'] = $workingHours[$key]->month0;
    //                 $total += $workingHours[$key]->working_hours;
    //             }
    //             if (isset($overtimeHours[$key])) {
    //                 $result0[$key]['overtime_hours'] = $overtimeHours[$key]->ot_hours;
    //                 $result0[$key]['month'] = $overtimeHours[$key]->month0;
    //                 $total += $overtimeHours[$key]->ot_hours;
    //             }
    //             if ($total > 0) {
    //                 $result0[$key]['total'] = $total;
    //             }
    //         }
    //     }
    //     // dump($result);
    //     $result = [];
    //     foreach ($result0 as $r) {
    //         if (!isset($result[$r['month']])) {
    //             $result[$r['month']] = 0;
    //         }
    //         $result[$r['month']] += $r['total'] ?? 0;
    //     }
    //    // dump($result);
    //     return $result;
    // }
}
