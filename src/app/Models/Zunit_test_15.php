<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_15 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'owner_id',];

    protected $table = 'zunit_test_15s';
    protected static $statusless = true;

    public $eloquentParams = [
        "getUT5" => ['hasMany', Zunit_test_05::class, 'parent_id'],
    ];

    public function getUT5()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
