<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Workplace extends ModelExtended
{
    protected $fillable = ["name", "description", "standard_working_hour", "def_assignee", "slug"];

    protected $table = 'workplaces';
    protected static $statusless = true;

    public $eloquentParams = [
        "getDefAssignee" => ["belongsTo", User::class, 'def_assignee'],

        "getUsers" => ['hasMany', User::class, 'workplace'],
        "getPublicHolidays" => ["hasMany", Public_holiday::class, 'workplace_id'],
        "getHrOtrs" => ["hasMany", Hr_overtime_request::class, 'workplace_id'],
    ];
    public $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getUsers()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefAssignee()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPublicHolidays()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getHrOtrs()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
