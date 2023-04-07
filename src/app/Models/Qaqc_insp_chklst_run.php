<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_run extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "qaqc_insp_chklst_sht_id", "status", "progress", "owner_id"];
    protected $table = "qaqc_insp_chklst_runs";
    public $nameless = true;

    public $eloquentParams = [
        "getOwnerId" => ["belongsTo", User::class, "owner_id"],
        "getLines" => ["hasMany", Qaqc_insp_chklst_run_line::class, "qaqc_insp_chklst_run_id"],
        "getSheet" => ["belongsTo", Qaqc_insp_chklst_sht::class, "qaqc_insp_chklst_sht_id"],
    ];
    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
