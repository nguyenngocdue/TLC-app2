<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Like extends ModelExtended
{
    protected $fillable = ["id", "post_id", "user_id", "owner_id"];
}
