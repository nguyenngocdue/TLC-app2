<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_15 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'owner_id',];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUT5" => ['hasMany', Zunit_test_05::class, 'parent_id'],
    ];

    public function getUT5()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
