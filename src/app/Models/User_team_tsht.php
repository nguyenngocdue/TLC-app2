<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_team_tsht extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "status", "owner_id", "def_assignee"];

    public static $eloquentParams = [
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],
        "getTshtMembers" => ['belongsToMany', User::class, 'ym2m_user_team_tsht_user_tsht_member',],
    ];

    public function getDefAssignee()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTshtMembers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
