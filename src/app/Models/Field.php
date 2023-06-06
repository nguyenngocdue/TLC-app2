<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Field extends ModelExtended
{
    protected $fillable = ['id', 'name', 'reversed_name', 'description', 'owner_id'];
    protected $table = "fields";
    protected static $statusless = true;

    public $eloquentParams = [];
}
