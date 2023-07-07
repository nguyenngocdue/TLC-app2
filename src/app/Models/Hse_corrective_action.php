<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_corrective_action extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'slug', 'correctable_type', 'correctable_id', 'priority_id', 'work_area_id',
        'assignee_1', 'opened_date', 'closed_at', 'status', 'unsafe_action_type_id', 'order_no', 'owner_id','remark','due_date'
    ];
    protected $table = "hse_corrective_actions";

    public static $eloquentParams = [
        "correctable" => ['morphTo', Hse_corrective_action::class, 'correctable_type', 'correctable_id'],
        'getWorkArea' => ['belongsTo', Work_area::class, 'work_area_id'],
        'getPriority' => ['belongsTo', Priority::class, 'priority_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        'getUnsafeActionType' => ['belongsTo', Term::class, 'unsafe_action_type_id'],
        "attachment_hse_car" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];
    public function attachment_hse_car()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function correctable()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
    public function getUnsafeActionType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWorkArea()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPriority()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getManyLineParams() //In HSE Incident Report
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'correctable_type', 'title' => 'Parent Type', 'invisible' => true, 'value_as_parent_type' => true],
            ['dataIndex' => 'correctable_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'priority_id',],
            ['dataIndex' => 'work_area_id'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'attachment_hse_car',],
            ['dataIndex' => 'assignee_1'],
            ['dataIndex' => 'opened_date'],
            ['dataIndex' => 'closed_at'],
            ['dataIndex' => 'status',],
            ['dataIndex' => 'unsafe_action_type_id',],
        ];
    }

    public function getManyLineParams2() //In HSE Walkthrough
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => !true],
            ['dataIndex' => 'correctable_type', 'title' => 'Parent Type', 'invisible' => true, 'value_as_parent_type' => true],
            ['dataIndex' => 'correctable_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name','title'=>"Observation Finding",'sub_title' => 'Phát hiện, quan sát'],
            ['dataIndex' => 'attachment_hse_car','title' => 'Pictures taken on site','sub_title'=> 'Hình ảnh tại hiện trường'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'assignee_1', 'title' => 'Action by'],
            ['dataIndex' => 'due_date'],
            // ['dataIndex' => 'status',],
            ['dataIndex' => 'unsafe_action_type_id',],
            ['dataIndex' => 'remark',],
        ];
    }

    public function getManyLineParams1() // Unit Test 10
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'hse_incident_report_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name', 'cloneable' => true],
            ['dataIndex' => 'work_area_id', 'hidden' => !true, 'cloneable' => true],
            ['dataIndex' => 'assignee_1', 'cloneable' => true],
            ['dataIndex' => 'opened_date', 'cloneable' => true],
            ['dataIndex' => 'status', 'cloneable' => true],
            ['dataIndex' => 'priority_id', 'cloneable' => true],
            ['dataIndex' => 'unsafe_action_type_id', 'cloneable' => true],
        ];
    }
}
