<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_task_phase extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'owner_id'];

    public static $statusless = true;
}
