<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_insp_group extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "owner_id"];
    protected $table = "hse_insp_groups";
    protected static $statusless = true;

    public $eloquentParams = [
        "getTemplateLines" => ["hasMany", Hse_insp_tmpl_line::class, "hse_insp_group_id"],
    ];

    public function getTemplateLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
