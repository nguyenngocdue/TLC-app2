<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_11 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'owner_id',];

    protected $table = 'zunit_test_11s';
    protected static $statusless = true;

    public $eloquentParams = [
        "getUT1" => ['hasMany', Zunit_test_01::class, 'parent_id'],
    ];

    public function getUT1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
