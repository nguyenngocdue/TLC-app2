<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_12 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description'];

    protected $table = 'zunit_test_12s';

    public $eloquentParams = [
        "getUT2" => ['hasMany', Zunit_test_02::class, 'parent_id'],
    ];

    public function getUT2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
