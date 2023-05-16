<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_metric_type_1 extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "esg_metric_type_id", "owner_id"];
    protected $table = "esg_metric_type_1s";

    public $eloquentParams = [
        "getEsgMetricType" => ['belongsTo', Esg_metric_type::class, 'esg_metric_type_id'],
        "getEsgMetricType2" => ['hasMany', Esg_metric_type_2::class, 'esg_metric_type_1_id'],

        "getOwner" => ['belongsTo', User::class, 'owner_id'],
        "getDeletedBy" => ['belongsTo', User::class, 'deleted_by'],
    ];
    public function getEsgMetricType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getEsgMetricType2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
