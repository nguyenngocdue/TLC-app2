<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Control_type extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'slug'];
    protected $table = "control_types";
}
