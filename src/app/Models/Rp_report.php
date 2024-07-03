<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_report extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "owner_id",
    ];

    public static $eloquentParams = [
        "getLines" => ["hasMany", Rp_page::class, "report_id"],
    ];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
