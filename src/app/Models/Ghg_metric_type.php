<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ghg_metric_type extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "owner_id", "ghg_tmpl_id"];
    public static $statusless = true;

    public static $eloquentParams = [
        "getGhgTmpl" => ['belongsTo', Ghg_tmpl::class, 'ghg_tmpl_id'],
        "getGhgMetricType1s" => ['hasMany', Ghg_metric_type_1::class, 'ghg_metric_type_id'],
    ];

    public function getGhgTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getGhgMetricType1s()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
