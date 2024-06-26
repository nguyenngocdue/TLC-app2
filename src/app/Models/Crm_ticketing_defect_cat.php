<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Crm_ticketing_defect_cat extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'owner_id', 'status',

    ];
    public static $statusless = true;

    public static $eloquentParams = [];
}
