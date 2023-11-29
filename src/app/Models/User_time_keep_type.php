<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_time_keep_type extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", 'owner_id'];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'time_keeping_type', 'id'],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
