<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_task_phase extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'owner_id',
        "show_in_task_budget", "order_no",
    ];

    public static $statusless = true;
}
