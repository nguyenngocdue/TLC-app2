<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_position extends ModelExtended
{
    protected $fillable = [
        "name", "description", "slug", "owner_id", "status",
        "position_prefix", "position_1", "position_2", "position_3",
        "report_to", "job_desc", "job_requirement",
        "report_to_1", "job_desc_1", "job_requirement_1",
        "assignee_1",
    ];

    // public static $statusless = true;
    public static $nameless = true;

    public function getNameAttribute($value)
    {
        $position_pres = User_position_pre::findFromCache($this->position_prefix);
        $position_pres = ($position_pres) ? $position_pres->name : "";
        $position_1 = User_position1::findFromCache($this->position_1);
        $position_1 = ($position_1) ? $position_1->name : "";
        $position_2 = User_position2::findFromCache($this->position_2);
        $position_2 = ($position_2) ? $position_2->name : "";
        $position_3 = User_position3::findFromCache($this->position_3);
        $position_3 = ($position_3) ? $position_3->name : "";

        $position_3 = $position_3 ? "($position_3)" : "";
        $str = "$position_pres $position_1 $position_2 $position_3";
        $str = trim(str_replace("  ", " ", $str));
        return $str;
        // $value = (new User_PositionRendered())($position_pres, $position_1, $position_2, $position_3);
    }

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'position'],
        "getPositionPrefix" => ['belongsTo', User_position_pre::class, 'position_prefix'],
        "getPosition1" => ['belongsTo', User_position1::class, 'position_1'],
        "getPosition2" => ['belongsTo', User_position2::class, 'position_2'],
        "getPosition3" => ['belongsTo', User_position3::class, 'position_3'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPositionPrefix()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPosition1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPosition2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPosition3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
