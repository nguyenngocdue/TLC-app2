<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_team extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'user_teams';

    
}
