<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_metric_type extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "owner_id",
        "esg_tmpl_id", "esg_code", "esg_state", "unit",
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getEsgTmpl" => ['belongsTo', Esg_tmpl::class, 'esg_tmpl_id'],
        "getUnit" => ['belongsTo', Term::class, 'unit'],
        "getState" => ['belongsTo', Term::class, 'esg_state'],
    ];

    public function getEsgTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUnit()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getState()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
