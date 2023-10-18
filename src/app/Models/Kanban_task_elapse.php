<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task_elapse extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'status', 'owner_id',
    ];

    public static $eloquentParams = [];

    public static $oracyParams = [];
}
