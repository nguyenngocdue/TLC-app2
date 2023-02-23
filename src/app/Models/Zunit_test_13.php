<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_13 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description'];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_13s';

    public $eloquentParams = [
        "getUT3" => ['hasMany', Zunit_test_03::class, 'parent_id'],
    ];

    public function getUT3()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
