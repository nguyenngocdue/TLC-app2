<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_control_group extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "owner_id"];
    public static $statusless = true;

    public static $eloquentParams = [
        "getControlValues" => ["hasMany", Qaqc_insp_control_value::class, "qaqc_insp_control_group_id"],
    ];

    public function getControlValues()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
