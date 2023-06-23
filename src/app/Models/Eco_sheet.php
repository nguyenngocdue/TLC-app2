<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "revision_no", "priority_id", "project_id",
        "assignee_1", "assignee_2", "assignee_3", "opened_date", "closed_at", "status", "unsafe_action_type_id", "order_no", "owner_id"
    ];
    protected $table = "eco_sheets";

    public $eloquentParams = [
        'getProject' => ['belongsTo', Project::class, 'project_id'],
        'getPriority' => ['belongsTo', Priority::class, 'priority_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        'getAssignee2' => ['belongsTo', User::class, 'assignee_2'],
        'getAssignee3' => ['belongsTo', User::class, 'assignee_3'],
        'getCurrency1' => ['belongsTo', Currency::class, 'currency_1'],
        'getCurrency2' => ['belongsTo', Currency::class, 'currency_2'],
        "attachment_eco_sht" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
        "getMonitors2()" => ["getCheckedByField", User::class],
        // subproject
    ];
    public function attachment_eco_sht()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee3()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPriority()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrency1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrency2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors2()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'hse_incident_report_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'priority_id',],
            ['dataIndex' => 'work_area_id'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'assignee_1'],
            ['dataIndex' => 'opened_date'],
            ['dataIndex' => 'closed_at'],
            ['dataIndex' => 'status',],
            ['dataIndex' => 'unsafe_action_type_id',],
        ];
    }

    public function getManyLineParams1()
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
