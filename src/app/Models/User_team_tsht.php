<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_team_tsht extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "status", "owner_id", "def_assignee"];

    public static $eloquentParams = [
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],
    ];

    public static $oracyParams = [
        "getTshtMembers()" => ["getCheckedByField", User::class],
    ];

    public function getDefAssignee()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTshtMembers()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
