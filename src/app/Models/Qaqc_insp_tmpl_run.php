<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_tmpl_run extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "qaqc_insp_tmpl_sht_id"];
    protected $table = "qaqc_insp_tmpl_runs";
    public $nameless = true;

    public $eloquentParams = [
        "getLines" => ["hasMany", Qaqc_insp_tmpl_line::class, "qaqc_insp_tmpl_run_id"],
    ];

    public function getLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
