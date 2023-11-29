<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ghg_tmpl extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description",
        "owner_id",
        "ghg_cat_id",
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getGhgCat" => ["belongsTo", Ghg_cat::class, "ghg_cat_id"],
        "getLines" => ["hasMany", Ghg_tmpl_line::class, "ghg_tmpl_id"],
    ];

    public function getGhgCat()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
