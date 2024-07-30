<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_listen_reducer extends ModelExtended
{
    protected $fillable = ['id', 'name', 'column_name', 'triggers', 'listen_to_fields', 'columns_to_set', 'attrs_to_compare',];
    public static $statusless = true;
}
