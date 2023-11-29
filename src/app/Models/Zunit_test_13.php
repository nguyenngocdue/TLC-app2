<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_13 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'owner_id',];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUT3" => ['hasMany', Zunit_test_03::class, 'parent_id'],
    ];

    public function getUT3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
