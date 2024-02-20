<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Conqa_archive extends ModelExtended
{
    protected $fillable = [
        "name", "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [];
    public static $oracyParams = [];
}
