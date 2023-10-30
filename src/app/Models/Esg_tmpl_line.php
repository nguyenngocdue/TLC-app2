<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_tmpl_line extends ModelExtended
{
    protected $fillable = [
        "id",  "owner_id", "order_no", "esg_tmpl_id",
        "esg_metric_type_id",
        // "ghg_metric_type_1_id", "ghg_metric_type_2_id",
        "unit", "remark",
        "status", "esg_code", "state",
    ];
    // protected $table = "ghg_tmpl_lines";

    public static $eloquentParams = [
        "getEsgTmpl" => ['belongsTo', Esg_tmpl::class, 'esg_tmpl_id'],
        "getEsgMetricType" => ['belongsTo', Esg_metric_type::class, 'esg_metric_type_id'],
        // "getGhgMetricType1" => ['belongsTo', Ghg_metric_type_1::class, 'ghg_metric_type_1_id'],
        // "getGhgMetricType2" => ['belongsTo', Ghg_metric_type_2::class, 'ghg_metric_type_2_id'],
        "getUnit" => ['belongsTo', Term::class, 'unit'],
        "getState" => ['belongsTo', Term::class, 'state'],
    ];

    public function getEsgTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getEsgMetricType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    // public function getGhgMetricType1()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }
    // public function getGhgMetricType2()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }
    public function getUnit()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getState()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {

        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'title' => 'OT Line ID', 'no_print' => true, 'invisible' => true],

            ['dataIndex' => 'esg_tmpl_id', 'invisible' => true, 'no_print' => true, 'value_as_parent_id' => true],

            ['dataIndex' => 'esg_metric_type_id'],
            // ['dataIndex' => 'ghg_metric_type_1_id'],
            // ['dataIndex' => 'ghg_metric_type_2_id'],

            // ['dataIndex' => 'factor'],
            ['dataIndex' => 'esg_code',],
            ['dataIndex' => 'state'],
            ['dataIndex' => 'unit'],
            ['dataIndex' => 'remark'],
        ];
    }
}
