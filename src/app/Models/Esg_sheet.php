<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_sheet extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "year", "available", "type"];
    protected $primaryKey = "id";
    protected $table = "esg_sheets";
}
