<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_14 extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'owner_id',
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getUsers" => ["hasMany", Zunit_test_14_user_detail::class, "zunit_test_14_id"],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
