<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_group extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug"];
    protected $table = "qaqc_insp_groups";

    public $eloquentParams = [
        "getTemplateLines" => ["hasMany", Qaqc_insp_tmpl_line::class, "qaqc_insp_group_id"],
        "getChklstLines" => ["hasMany", Qaqc_insp_chklst_line::class, "qaqc_insp_group_id"],
    ];

    public function getTemplateLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getChklstLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
