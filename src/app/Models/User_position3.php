<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_position3 extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];

    protected $table = 'user_position3s';
    protected static $statusless = true;

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'position_3', 'id'],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
