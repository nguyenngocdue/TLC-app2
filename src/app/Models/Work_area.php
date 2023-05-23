<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Work_area extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'slug'];
    protected $table = "work_areas";
    protected static $statusless = true;
}
