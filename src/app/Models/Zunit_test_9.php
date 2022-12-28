<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_9 extends ModelExtended
{
    protected $fillable = [];
    protected $table = "zunit_test_9s";
    public $menuTitle = "UT09 (Basic Event)";

    public $eloquentParams = [
        "department1" => ['belongsTo', Department::class, 'department_1'],
        "user1" => ['belongsTo', Department::class, 'user_1'],
        "department2" => ['belongsTo', Department::class, 'department_2'],
        "user2" => ['belongsTo', Department::class, 'user_2'],
        "category" => ['belongsTo', Workplace::class, 'category'],
    ];

    public function department1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function department2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function category()

    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function user1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function user2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
