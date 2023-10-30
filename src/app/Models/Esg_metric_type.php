<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_metric_type extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "owner_id", "esg_tmpl_id"];
    public static $statusless = true;

    public static $eloquentParams = [
        "getEsgTmpl" => ['belongsTo', Esg_tmpl::class, 'esg_tmpl_id'],
    ];

    public function getEsgTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
