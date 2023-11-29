<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_11 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'owner_id',];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUT1" => ['hasMany', Zunit_test_01::class, 'parent_id'],
        "getDepartments" => ['hasMany', Department::class, 'parent_id'],
    ];

    public function getUT1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDepartments()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
