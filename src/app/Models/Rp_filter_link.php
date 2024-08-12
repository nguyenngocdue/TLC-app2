<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_filter_link extends ModelExtended
{
    protected $fillable = ["id", "name", "title", "linked_to_report_id", "stored_filter_key", "owner_id"];

    public static $eloquentParams = [
        "getReportLinks" => ["belongsTo", Rp_report::class, "linked_to_report_id"],
    ];

    public function getReportLinks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
