<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_control_value extends ModelExtended
{
    protected $fillable = ["id", "control_group", "name", "description"];
    protected $table = "qaqc_insp_control_values";

    public $eloquentParams = [
        "getValues" => ["hasMany", Qaqc_insp_value::class, "qaqc_insp_control_value_id"],
        "getControlGroup" => ["belongsTo", Qaqc_insp_control_group::class, "qaqc_insp_control_group_id"],
    ];
    public function getValues()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlGroup()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
