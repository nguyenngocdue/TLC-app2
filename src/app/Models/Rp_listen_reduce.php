<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_listen_reduce extends ModelExtended
{
    protected $fillable = ['id', 'name', 'trigger', 'listen_to_field', 'columns_to_set', 'attrs_to_compare',];
    public static $statusless = true;
}
