<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_travel_place extends ModelExtended
{
    protected $fillable = ["name", "description", "status","owner_id"];
    protected $table = "act_travel_places";
}
