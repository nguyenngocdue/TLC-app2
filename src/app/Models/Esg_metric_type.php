<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_metric_type extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "owner_id"];
    protected $table = "esg_metric_types";

    public $eloquentParams = [
        "getEsgMetricType1s" => ['hasMany', Esg_metric_type_1::class, 'esg_metric_type_id'],
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],
        "getDeleteBy" => ['belongsTo', User::class, 'deleted_by'],
    ];

    public function getEsgMetricType1s()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
