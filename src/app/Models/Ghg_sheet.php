<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ghg_sheet extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "year", "available", "type", "owner_id"];
    protected $table = "ghg_sheets";

    public $eloquentParams = [
        "getLines" => ["hasMany", Ghg_line::class, "ghg_sheet_id"],
    ];

    public function getLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
