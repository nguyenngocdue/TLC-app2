<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_team_ncr extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];

    protected $table = 'user_team_ncrs';
    protected static $statusless = true;
}
