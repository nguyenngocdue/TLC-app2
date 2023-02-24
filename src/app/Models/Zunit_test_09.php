<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_09 extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        //Reduce 11
        'department_2',
        'user_2',
        'category_id',
        'user_3',
        'ot_date_1',
        'remaining_hours',
        //Reduce 1111
        'project_1',
        'sub_project_1',
        'prod_routing_1',
        'prod_order_1',
        //Assign
        'prod_discipline_1',
        'assignee_1',
        'user_4',
        'user_position_1',
        //Dot
        'department_1',
        'user_1',
        "priority_id",
        "due_date",
        //Expression
        'total',
        'accepted',
        'rejected',
        'remaining',
        //Expression Date Time
        'datetime_1',
        'datetime_2',
        'datetime_3',
        'date_1',
        'date_2',
        'date_3',
        'time_1',
        'time_2',
        'time_3',

        'parent_id',
        "order_no",
    ];
    protected $table = "zunit_test_09s";

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
        "getPriority" => ['belongsTo', Priority::class, 'priority_id'],

    ];

    public $oracyParams = [
        "dropdownMonitors()" => ["getCheckedByField", User::class],
    ];

    public function getManyLineParamsReduce11()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'department_2'],
            ['dataIndex' => 'user_2'],
            ['dataIndex' => 'category_id'],
            ['dataIndex' => 'user_3'],
        ];
    }
    public function getManyLineParamsReduce1111()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'project_1'],
            ['dataIndex' => 'sub_project_1'],
            ['dataIndex' => 'prod_routing_1'],
            ['dataIndex' => 'prod_order_1'],
        ];
    }
    public function getManyLineParamsAssign()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name', 'cloneable' => true],
            ['dataIndex' => 'prod_discipline_1', 'cloneable' => true],
            ['dataIndex' => 'assignee_1', 'cloneable' => true],
            ['dataIndex' => 'dropdownMonitors()', 'cloneable' => true],
        ];
    }
    public function getManyLineParamsDot()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'department_1'],
            ['dataIndex' => 'user_1'],
            ['dataIndex' => 'user_4'],
            ['dataIndex' => 'user_position_1'],
        ];
    }
    public function getManyLineParamsDateOffset()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => "priority_id"],
            ['dataIndex' => "due_date"],

        ];
    }
    public function getManyLineParamsExpression()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => "total"],
            ['dataIndex' => "accepted"],
            ['dataIndex' => "rejected"],
            ['dataIndex' => "remaining"],
        ];
    }
    public function getManyLineParamsExpressionDateTime()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            // ['dataIndex' => "date_1"],
            // ['dataIndex' => "date_2"],
            // ['dataIndex' => "date_3"],
            ['dataIndex' => "time_1"],
            ['dataIndex' => "time_2"],
            ['dataIndex' => "time_3"],
        ];
    }

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
    public function getPriority()
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
