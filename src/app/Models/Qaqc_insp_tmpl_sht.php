<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_tmpl_sht extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "qaqc_insp_tmpl_id"];
    protected $table = "qaqc_insp_tmpl_shts";

    public $eloquentParams = [
        "getRuns" => ["hasMany", Qaqc_insp_tmpl_run::class, "qaqc_insp_tmpl_sht_id"],
        "getTmpl" => ["belongsTo", Qaqc_insp_tmpl::class, 'qaqc_insp_tmpl_id'],
    ];

    public function getRuns()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTmpl()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
