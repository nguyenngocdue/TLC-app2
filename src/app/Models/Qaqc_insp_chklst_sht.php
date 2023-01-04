<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_sht extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "qaqc_insp_chklst_sht_id"];
    protected $table = "qaqc_insp_chklst_shts";

    public $eloquentParams = [
        "getChklstLines" => ["hasMany", Qaqc_insp_chklst_line::class, "qaqc_insp_chklst_sht_id"],
    ];

    public function getChklstLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
