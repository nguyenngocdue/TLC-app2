<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_10 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'owner_id',];

    public static $statusless = true;

    public static $eloquentParams = [
        // 'getCorrectiveActions' => ['hasMany', Hse_corrective_action::class, 'hse_incident_report_id'],
        "getCorrectiveActions" => ['morphMany', Hse_corrective_action::class, 'correctable', 'correctable_type', 'correctable_id'],
        "getDiscipline1" => ['hasMany', Prod_discipline_1::class, 'prod_discipline_id'],
        "getUT1" => ['hasMany', Zunit_test_01::class, 'parent_id'],
        "getUT2" => ['hasMany', Zunit_test_02::class, 'parent_id'],
        "getUT3" => ['hasMany', Zunit_test_03::class, 'parent_id'],
        "getUT9Reduce11" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Reduce1111" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Assign" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Dot" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9DateOffset" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Expression" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9ExpressionDateTime" => ['hasMany', Zunit_test_09::class, 'parent_id'],
    ];

    public function getCorrectiveActions()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDiscipline1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
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
