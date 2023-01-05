<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_9 extends ModelExtended
{
    protected $fillable = ['content', 'department_1', 'department_2', 'category_id', 'sub_project',  'user_1', 'user_2', 'user_3', 'prod_order_1'];
    protected $table = "zunit_test_9s";
    public $menuTitle = "UT09 (Basic Event)";

    public $eloquentParams = [
        "department1" => ['belongsTo', Department::class, 'department_1'],
        "user1" => ['belongsTo', User::class, 'user_1'],

        "department2" => ['belongsTo', Department::class, 'department_2'],
        "user2" => ['belongsTo', User::class, 'user_2'],

        "category" => ['belongsTo', User_category::class, 'category_id'],
        "user3" => ['belongsTo', User::class, 'user_3'],

        "getSubProject1" => ['belongsTo', Sub_project::class, 'sub_project'],
        "getProdOrder1" => ['belongsTo', Prod_order::class, 'prod_order_1'],
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
    public function user3()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function category()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSubProject1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getProdOrder1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
