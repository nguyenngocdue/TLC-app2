<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_10 extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'owner_id',

        'prod_routing_1_id',
        'prod_routing_2_id',

        'sub_project_5a_id',
        'prod_routing_5_id',

        'sub_project_6a_id',
        'prod_routing_6_id',

        'sub_project_7a_id',
        'sub_project_8a_id',

    ];

    public static $statusless = true;

    public static $eloquentParams = [
        // ∩
        "getSubProjects_1" => ["belongsToMany", Sub_project::class, "ym2m_sub_project_zunit_test_10_case_1"],
        "getProdRouting_1" => ["belongsTo", Prod_routing::class, "prod_routing_1_id"],
        // ∪
        "getSubProjects_2" => ["belongsToMany", Sub_project::class, "ym2m_sub_project_zunit_test_10_case_2"],
        "getProdRouting_2" => ["belongsTo", Prod_routing::class, "prod_routing_2_id"],
        // ∩
        "getSubProjects_3" => ["belongsToMany", Sub_project::class, "ym2m_sub_project_zunit_test_10_case_3"],
        "getProdRoutings_3" => ["belongsToMany", Prod_routing::class, "ym2m_prod_routing_zunit_test_10_case_3"],
        // ∪        
        "getSubProjects_4" => ["belongsToMany", Sub_project::class, "ym2m_sub_project_zunit_test_10_case_4"],
        "getProdRoutings_4" => ["belongsToMany", Prod_routing::class, "ym2m_prod_routing_zunit_test_10_case_4"],
        // ∩
        "getSubProject_5a" => ["belongsTo", Sub_project::class, "sub_project_5a_id"],
        "getSubProjects_5b" => ["belongsToMany", Sub_project::class, "ym2m_sub_project_zunit_test_10_case_5"],
        "getProdRouting_5" => ["belongsTo", Prod_routing::class, "prod_routing_5_id"],
        // ∪        
        "getSubProject_6a" => ["belongsTo", Sub_project::class, "sub_project_6a_id"],
        "getSubProjects_6b" => ["belongsToMany", Sub_project::class, "ym2m_sub_project_zunit_test_10_case_6"],
        "getProdRouting_6" => ["belongsTo", Prod_routing::class, "prod_routing_6_id"],
        // ∩
        "getSubProject_7a" => ["belongsTo", Sub_project::class, "sub_project_7a_id"],
        "getSubProjects_7b" => ["belongsToMany", Sub_project::class, "ym2m_sub_project_zunit_test_10_case_7"],
        "getProdRoutings_7" => ["belongsToMany", Prod_routing::class, "ym2m_prod_routing_zunit_test_10_case_7"],
        // ∪
        "getSubProject_8a" => ["belongsTo", Sub_project::class, "sub_project_8a_id"],
        "getSubProjects_8b" => ["belongsToMany", Sub_project::class, "ym2m_sub_project_zunit_test_10_case_8"],
        "getProdRoutings_8" => ["belongsToMany", Prod_routing::class, "ym2m_prod_routing_zunit_test_10_case_8"],
    ];

    public function getSubProjects_1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRouting_1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProjects_2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRouting_2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProjects_3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutings_3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProjects_4()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutings_4()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProject_5a()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProjects_5b()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRouting_5()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProject_6a()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProjects_6b()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRouting_6()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProject_7a()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProjects_7b()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutings_7()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProject_8a()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProjects_8b()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutings_8()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
