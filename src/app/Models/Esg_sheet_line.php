<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_sheet_line extends ModelExtended
{
    protected $fillable = [
        "id",  "owner_id", "order_no", "ghg_tmpl_id",
        "ghg_metric_type_id", "ghg_metric_type_1_id", "ghg_metric_type_2_id",
        "unit", "factor", "ghg_sheet_id", "value", "total",
        "remark", "status",
    ];
    protected $table = "ghg_sheet_lines";
    public static $nameless = true;

    public static $eloquentParams = [
        "getGhgSheet" => ['belongsTo', Ghg_sheet::class, 'ghg_sheet_id'],
        "getGhgTmpl" => ['belongsTo', Ghg_tmpl::class, 'ghg_tmpl_id'],
        "getGhgMetricType" => ['belongsTo', Ghg_metric_type::class, 'ghg_metric_type_id'],
        "getGhgMetricType1" => ['belongsTo', Ghg_metric_type_1::class, 'ghg_metric_type_1_id'],
        "getGhgMetricType2" => ['belongsTo', Ghg_metric_type_2::class, 'ghg_metric_type_2_id'],
        "getUnit" => ['belongsTo', Term::class, 'unit'],
    ];
    public function getGhgSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getGhgTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getGhgMetricType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getGhgMetricType1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getGhgMetricType2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUnit()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'title' => 'OT Line ID', 'no_print' => true, 'invisible' => true],
            ['dataIndex' => 'ghg_sheet_id', 'invisible' => true, 'value_as_parent_id' => true],

            ['dataIndex' => 'ghg_tmpl_id', 'invisible' => true, 'no_print' => true],

            ['dataIndex' => 'ghg_metric_type_id'],
            ['dataIndex' => 'ghg_metric_type_1_id'],
            ['dataIndex' => 'ghg_metric_type_2_id'],

            ['dataIndex' => 'factor', 'invisible' => true,],
            ['dataIndex' => 'value'],
            ['dataIndex' => 'unit'],
            ['dataIndex' => 'total', 'footer' => "agg_sum",],

            ['dataIndex' => 'remark', 'cloneable' => true],
            ['dataIndex' => 'status', 'cloneable' => true, 'no_print' => true, 'invisible' => true],
        ];
    }
}
