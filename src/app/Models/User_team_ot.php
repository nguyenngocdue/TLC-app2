<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_team_ot extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "status", "owner_id", "def_assignee"];

    public static $eloquentParams = [
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],

        "getOtMembers" => ["belongsToMany", User::class, 'ym2m_user_team_ot_user_ot_member'],
    ];

    public function getDefAssignee()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getOtMembers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
