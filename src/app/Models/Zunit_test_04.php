<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_04 extends ModelExtended
{
    protected $fillable = ['id'];
    protected $table = "zunit_test_04s";
    protected static $statusless = true;

    public $eloquentParams = [];
}
