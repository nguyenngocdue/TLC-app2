<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_metric_type_2 extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "esg_metric_type_1_id", "owner_id", "factor", "unit"];
    protected $table = "esg_metric_type_2s";

    public $eloquentParams = [
        "getEsgMetricType1" => ['belongsTo', Esg_metric_type_1::class, 'esg_metric_type_1_id'],

        "getUnit" => ['belongsTo', Term::class, 'unit'],
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],
        "getDeleteBy" => ['belongsTo', User::class, 'deleted_by'],
    ];

    public function getEsgMetricType1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
