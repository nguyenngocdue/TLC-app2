<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ghg_metric_type_1 extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "ghg_metric_type_id", "owner_id"];
    protected $table = "ghg_metric_type_1s";

    public $eloquentParams = [
        "getGhgMetricType" => ['belongsTo', Ghg_metric_type::class, 'ghg_metric_type_id'],
        "getGhgMetricType2" => ['hasMany', Ghg_metric_type_2::class, 'ghg_metric_type_1_id'],
    ];
    public function getGhgMetricType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getGhgMetricType2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
