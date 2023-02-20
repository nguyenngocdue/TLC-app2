<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Field extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'slug'];
    protected $table = "fields";

    public $eloquentParams = [];

}
