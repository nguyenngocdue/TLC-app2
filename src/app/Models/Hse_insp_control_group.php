<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_insp_control_group extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "owner_id"];
    protected $table = "hse_insp_control_groups";
    protected static $statusless = true;

    public $eloquentParams = [
        "getControlValues" => ["hasMany", Hse_insp_control_value::class, "hse_insp_control_group_id"],
    ];

    public function getControlValues()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
