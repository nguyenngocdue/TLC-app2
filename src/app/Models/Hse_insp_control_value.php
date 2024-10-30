<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_insp_control_value extends ModelExtended
{
    protected $fillable = ["id", "control_group", "name", "description", 'hse_insp_control_group_id', 'owner_id'];
    public static $statusless = true;

    public static $eloquentParams = [
        "getControlGroup" => ["belongsTo", Hse_insp_control_group::class, "hse_insp_control_group_id"],
        "getValues" => ["hasMany", Hse_insp_value::class, "hse_insp_control_value_id"],
    ];

    public function getValues()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getControlGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
