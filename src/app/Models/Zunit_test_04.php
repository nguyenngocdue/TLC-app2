<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_04 extends ModelExtended
{
    protected $fillable = ['id', 'owner_id',];
    public static $statusless = true;

    public static $eloquentParams = [];
}
