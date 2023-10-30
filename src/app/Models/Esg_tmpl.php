<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_tmpl extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description",
        "owner_id",
        // "ghg_cat_id",
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        // "getGhgCat" => ["belongsTo", Ghg_cat::class, "ghg_cat_id"],
        "getLines" => ["hasMany", Esg_tmpl_line::class, "esg_tmpl_id"],
    ];

    // public function getGhgCat()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }
    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
