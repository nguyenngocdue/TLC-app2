<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_insp_value extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "owner_id"];
    protected $table = "hse_insp_values";
    protected static $statusless = true;

    public $eloquentParams = [
        "getControlValue" => ["belongsTo", Hse_insp_control_value::class, 'hse_insp_control_value_id'],
    ];

    public function getControlValue()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
