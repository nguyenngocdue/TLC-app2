<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_extra_metric extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "owner_id","","total_discipline","total_third_party_insp_audit","total_drill"];
    protected $table = "hse_extra_metrics";
    protected static $statusless = true;

    public static $eloquentParams = [
    ];
}
