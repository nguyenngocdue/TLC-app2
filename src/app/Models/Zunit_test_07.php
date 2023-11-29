<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_07 extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'status',

        'owner_id',
        'parent_id',

        'assignee_1',
        'assignee_2',

        "order_no",
        'closed_at',
    ];

    public static $eloquentParams = [
        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "getAssignee1" => ["belongsTo", User::class, 'assignee_1'],
        "getAssignee2" => ["belongsTo", User::class, 'assignee_2'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
        "getMonitors2()" => ["getCheckedByField", User::class],
        "getMonitors3()" => ["getCheckedByField", User::class],
        "getMonitors4()" => ["getCheckedByField", User::class],
        "getMonitors5()" => ["getCheckedByField", User::class],
        "getMonitors6()" => ["getCheckedByField", User::class],
        "getMonitors7()" => ["getCheckedByField", User::class],
        "getMonitors8()" => ["getCheckedByField", User::class],
        "getMonitors9()" => ["getCheckedByField", User::class],
    ];

    public function comment_rejected_reason()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors2()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors3()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors4()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors5()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors6()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors7()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors8()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors9()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
