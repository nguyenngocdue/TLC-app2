<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_team_site extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "status", "owner_id", "def_assignee"];

    public static $eloquentParams = [
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],
    ];

    public static $oracyParams = [
        "getSiteMembers()" => ["getCheckedByField", User::class],
    ];

    public function getDefAssignee()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSiteMembers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
