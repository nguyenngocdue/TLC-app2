<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Http\Traits\HasStatus;

class Qaqc_insp_chklst_run extends ModelExtended
{
    use HasStatus;

    protected $fillable = ["id", "name", "description", "slug", "qaqc_insp_chklst_sht_id", "status", "progress", "owner_id"];
    protected $table = "qaqc_insp_chklst_runs";
    public $nameless = true;

    public $eloquentParams = [
        "user" => ["belongsTo", User::class, "owner_id"],
        "getLines" => ["hasMany", Qaqc_insp_chklst_line::class, "qaqc_insp_chklst_run_id"],
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
