<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_block extends ModelExtended
{
    protected $fillable = ["id", "title", "sql_string", "owner_id"];
}
