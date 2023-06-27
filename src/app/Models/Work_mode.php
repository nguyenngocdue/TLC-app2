<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Work_mode extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];

    protected $table = 'work_modes';
    protected static $statusless = true;

    public static $eloquentParams = [];
}
