<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_sheet extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "year", "available", "type", "owner_id"];
    protected $table = "esg_sheets";

    public $eloquentParams = [
        "getLines" => ["hasMany", Esg_line::class, "esg_sheet_id"],
    ];

    public function getLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
