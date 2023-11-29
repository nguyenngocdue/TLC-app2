<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_extra_metric extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "owner_id",
        "total_discipline", "total_third_party_insp_audit", "total_drill",
        'metric_month', "total_work_hours", "total_meeting_toolbox", "workplace_id",
        "status",
    ];
    // public static $statusless = true;
    public static $nameless = true;

    public static $eloquentParams = [
        'getWorkplace' => ['belongsTo', Workplace::class, 'workplace_id'],
    ];

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
