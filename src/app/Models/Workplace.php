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
    public function getDurationMorning()
    {
        $startTime =  Carbon::parse($this->standard_start_time);
        $startBreakTime =  Carbon::parse($this->standard_start_break);
        return $startBreakTime->diffInMinutes($startTime);
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
}
