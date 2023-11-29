<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_currency extends ModelExtended
{
    protected $fillable = ["name", "description", "status", "owner_id"];
}
