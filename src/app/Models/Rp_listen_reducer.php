<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_listen_reducer extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'column_name', 'triggers', 
        'listen_to_fields', 'listen_to_attrs',
        'columns_to_set', 'attrs_to_compare', 'owner_id',];
    public static $statusless = true;
}
