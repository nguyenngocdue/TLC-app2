<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_discipline extends ModelExtended
{
    protected $fillable = ["name", "description", "def_assignee", "slug"];

    protected $table = 'user_disciplines';
    protected static $statusless = true;

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'discipline', 'id'],
        "getDefAssignee" => ["belongsTo", User::class, 'def_assignee'],
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
    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
