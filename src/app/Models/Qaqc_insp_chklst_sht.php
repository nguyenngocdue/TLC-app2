<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_sht extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "qaqc_insp_chklst_id"];
    protected $table = "qaqc_insp_chklst_shts";

    public $eloquentParams = [
        "getRuns" => ["hasMany", Qaqc_insp_chklst_run::class, "qaqc_insp_chklst_sht_id"],
    ];

    public function getRuns()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2]);
        $sql = $relation
            ->getQuery()
            ->orderBy('created_at', 'DESC')
            ->toSql();
        return $relation;
    }
}
