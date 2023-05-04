<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_metric_type_2 extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "esg_metric_type_id"];
    protected $primaryKey = 'id';
    protected $table = "esg_metric_type_2s";

    public $eloquentParams = [
        "getEsgMetricType1" => ['belongsTo', Esg_metric_type_1::class, 'esg_metric_type_1_id'],
    ];
}
