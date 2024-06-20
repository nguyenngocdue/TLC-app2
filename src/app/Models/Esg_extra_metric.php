<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_extra_metric extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "owner_id", "status",
        "workplace_id",
        "metric_month",
        "trained_employees",
        "internal_discriminations",
        "internal_grievances",
        "external_grievances",
        "onsite_workers_contractors",
        "number_of_part_time_female",
        "number_of_part_time_male",
        "working_hours_of_part_time",
    ];
    // public static $statusless = true;
    public static $nameless = true;
    // public function getNameAttribute($value)
    // {
    //     $workplaceName = $this->getWorkplace->name;
    //     return $workplaceName . " " . $this->metric_month;
    // }

    public static $eloquentParams = [
        'getWorkplace' => ['belongsTo', Workplace::class, 'workplace_id'],
    ];

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
