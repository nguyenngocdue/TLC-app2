<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ghg_metric_type_2 extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "ghg_metric_type_1_id", "owner_id", "factor", "unit"];
    protected $table = "ghg_metric_type_2s";

    public $eloquentParams = [
        "getGhgMetricType1" => ['belongsTo', Ghg_metric_type_1::class, 'ghg_metric_type_1_id'],
        "getUnit" => ['belongsTo', Term::class, 'unit'],
    ];

    public function getGhgMetricType1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUnit()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
