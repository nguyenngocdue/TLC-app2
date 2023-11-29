<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_19 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'owner_id',];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUT9Reduce11" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Reduce1111" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Assign" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Dot" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9DateOffset" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Expression" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9ExpressionDateTime" => ['hasMany', Zunit_test_09::class, 'parent_id'],
    ];

    public function getUT9Reduce11()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9Reduce1111()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9Assign()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9Dot()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9DateOffset()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9Expression()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9ExpressionDateTime()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
