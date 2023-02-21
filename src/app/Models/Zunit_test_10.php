<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_10 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description'];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_10s';

    public $eloquentParams = [
        'getCorrectiveActions' => ['hasMany', Hse_corrective_action::class, 'hse_incident_report_id'],
        "getDiscipline1" => ['hasMany', Prod_discipline_1::class, 'prod_discipline_id'],
        "getUT1" => ['hasMany', Zunit_test_01::class, 'parent_id'],
        "getUT2" => ['hasMany', Zunit_test_02::class, 'parent_id'],
        "getUT9Reduce11" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Reduce1111" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Assign" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Dot" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9DateOffset" => ['hasMany', Zunit_test_09::class, 'parent_id'],
        "getUT9Expression" => ['hasMany', Zunit_test_09::class, 'parent_id'],
    ];

    public function getCorrectiveActions()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDiscipline1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9Reduce11()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9Reduce1111()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9Assign()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9Dot()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9DateOffset()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUT9Expression()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
