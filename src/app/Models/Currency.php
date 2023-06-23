<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Currency extends ModelExtended
{
    protected $fillable = ["name", "description", "status"];
    protected $table = "currencies";
}
