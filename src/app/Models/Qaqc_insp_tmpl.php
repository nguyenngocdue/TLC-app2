<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_tmpl extends ModelExtended
{
    protected $fillable = ["id", "prod_routing_id", "name", "description", "slug"];
    protected $table = "qaqc_insp_tmpls";

    public $eloquentParams = [
        "getLines" => ["hasMany", Qaqc_insp_tmpl_line::class, "qaqc_insp_tmpl_id"],
        "getProdRouting" => ["belongsTo", Prod_routing::class, "prod_routing_id"],
    ];

    public function getLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getProdRouting()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
