<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class It_ticket_cat extends ModelExtended
{
    protected $fillable = [
        "name",
        "description",
        "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [];
}
