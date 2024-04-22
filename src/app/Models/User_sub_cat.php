<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_sub_cat extends ModelExtended
{
    public static $statusless = true;

    protected $fillable = [
        "name", "description", "slug", "owner_id",
        "user_type_id", "user_category_id",
    ];

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'erp_sub_cat'],
        "getUserType" => ['belongsTo', User_type::class, 'user_type_id'],
        "getUserCategory" => ['belongsTo', User_category::class, 'user_category_id'],
    ];

    public static $nameless = true;
    public function getNameAttribute($value)
    {
        return $this->getUserCategory->name . "-" . $this->getUserType->erp_code;
    }

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUserType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUserCategory()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
