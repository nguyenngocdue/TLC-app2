<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_seniority_level extends ModelExtended
{
    protected $fillable = ["name", "description", "owner_id"];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'seniority_level'],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
