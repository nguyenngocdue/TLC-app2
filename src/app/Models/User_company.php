<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_company extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "owner_id"];
    protected $table = 'user_companies';

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'company'],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
