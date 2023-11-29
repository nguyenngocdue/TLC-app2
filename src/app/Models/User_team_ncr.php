<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_team_ncr extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];

    public static $statusless = true;
}
