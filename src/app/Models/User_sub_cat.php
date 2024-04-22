<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_sub_cat extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "user_type_id", "user_category_id"];

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'sub_cat_id'],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
