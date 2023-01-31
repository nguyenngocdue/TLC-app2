<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_9 extends ModelExtended
{
    protected $fillable = [
        'content',
        'department_1',
        'user_1',
        'department_2',
        'user_2',
        'category_id',
        'user_3',
        'project_1',
        'sub_project_1',
        'prod_routing_1',
        'prod_order_1',
        'prod_discipline_1',
        'assignee_1',
        'user_4',
        'user_position_1',
    ];
    protected $table = "zunit_test_9s";

    public $eloquentParams = [
        "department1" => ['belongsTo', Department::class, 'department_1'],
        "user1" => ['belongsTo', User::class, 'user_1'],

        "department2" => ['belongsTo', Department::class, 'department_2'],
        "user2" => ['belongsTo', User::class, 'user_2'],

        "category" => ['belongsTo', User_category::class, 'category_id'],
        "user3" => ['belongsTo', User::class, 'user_3'],

        "getProject1" => ['belongsTo', Project::class, 'project_1'],
        "getSubProject1" => ['belongsTo', Sub_project::class, 'sub_project_1'],
        "getProdRouting1" => ['belongsTo', Prod_routing::class, 'prod_routing_1'],
        "getProdOrder1" => ['belongsTo', Prod_order::class, 'prod_order_1'],

        "getProdDiscipline1" => ['belongsTo', Prod_discipline::class, 'prod_discipline_1'],
        "getAssignee1" => ['belongsTo', User::class, 'assignee_1'],

        "user4" => ['belongsTo', User::class, 'user_4'],
    ];

    public $oracyParams = [
        "dropdownMonitors()" => ["getCheckedByField", User::class],
    ];

    public function dropdownMonitors()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

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
    public function getProject1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getProdRouting1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getProdDiscipline1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function user4()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
