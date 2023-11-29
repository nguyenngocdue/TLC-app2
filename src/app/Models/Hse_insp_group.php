<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_insp_group extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "owner_id"];
    public static $statusless = true;

    public static $eloquentParams = [
        "getTemplateLines" => ["hasMany", Hse_insp_tmpl_line::class, "hse_insp_group_id"],
    ];

    public function getTemplateLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
