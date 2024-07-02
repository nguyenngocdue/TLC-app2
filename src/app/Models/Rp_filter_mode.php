<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_filter_mode extends ModelExtended
{
    protected $fillable = ["id", "name", "report_id", "owner_id"];

    public static $eloquentParams = [
        "getParent" => ['belongsTo', Rp_report::class, 'report_id'],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
