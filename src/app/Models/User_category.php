<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_category extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'category'],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
