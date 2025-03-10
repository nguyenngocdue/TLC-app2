<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_position_pre extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'position_prefix'],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
