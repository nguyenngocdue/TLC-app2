<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Priority extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'priorities';

    
}
