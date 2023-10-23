<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task_transition extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'status', 'owner_id',
        "kanban_task_id", "kanban_group_id", "start_at",
    ];

    public static $eloquentParams = [];

    public static $oracyParams = [];
}
