<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_type extends ModelExtended
{
    protected $fillable = [
        "name", "description", "slug",
        "erp_code",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'user_type', 'id'],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
