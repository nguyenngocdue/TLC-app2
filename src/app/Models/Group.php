<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Group extends ModelExtended
{
    protected $fillable = ["group_id", "owner_id","confirmed_at"];

    protected $table = 'groups';

    public static $eloquentParams = [
       
    ];

    
}
