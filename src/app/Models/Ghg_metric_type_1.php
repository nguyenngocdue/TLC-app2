<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ghg_metric_type_1 extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "ghg_metric_type_id", "owner_id", "order_no"];
    public static $statusless = true;

    public static $eloquentParams = [
        "getGhgMetricType" => ['belongsTo', Ghg_metric_type::class, 'ghg_metric_type_id'],
        "getGhgMetricType2" => ['hasMany', Ghg_metric_type_2::class, 'ghg_metric_type_1_id'],
    ];
    public function getGhgMetricType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getGhgMetricType2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return    [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'no_print' => true, 'invisible' => !true],
            ['dataIndex' => 'ghg_metric_type_id', 'invisible' => true, 'value_as_parent_id' => true],

            ["dataIndex" => 'name',],
        ];
    }
}
