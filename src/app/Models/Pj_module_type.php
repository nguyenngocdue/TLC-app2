<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_module_type extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [];
}
