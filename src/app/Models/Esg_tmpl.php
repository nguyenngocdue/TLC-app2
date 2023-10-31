<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_tmpl extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description",
        "owner_id", "unit",
        // "ghg_cat_id",
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getUnit" => ['belongsTo', Term::class, 'unit'],
        "getLines" => ["hasMany", Esg_tmpl_line::class, "esg_tmpl_id"],
    ];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUnit()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
