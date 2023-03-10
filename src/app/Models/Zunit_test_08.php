<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_08 extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'status',

        'owner_id',
        'parent_id',
        "order_no",
    ];
    protected $table = "zunit_test_08s";
}
