<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_org_chart extends ModelExtended
{
    protected $fillable = ["name", "description", "def_assignee", "owner_id"];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'org_chart', 'id'],
        "getDefAssignee" => ["belongsTo", User::class, 'def_assignee'],
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
}
