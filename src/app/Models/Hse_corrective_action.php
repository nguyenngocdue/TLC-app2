<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_corrective_action extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'slug', 'hse_incident_report_id', 'priority_id', 'work_area_id',
        'assignee_1', 'opened_date', 'closed_date', 'status', 'unsafe_action_type_id', 'order_no', 'owner_id'
    ];
    protected $table = "hse_corrective_actions";

    public $eloquentParams = [
        'getHseIncidentReport' => ['belongsTo', Hse_incident_report::class, 'hse_incident_report_id'],
        'getWorkArea' => ['belongsTo', Work_area::class, 'work_area_id'],
        'getPriority' => ['belongsTo', Priority::class, 'priority_id'],
        'getAssignee' => ['belongsTo', User::class, 'assignee_1'],
        'getUnsafeActionType' => ['belongsTo', Term::class, 'unsafe_action_type_id'],
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],
    ];

    public $oracyParams = [
        "getMonitors()" => ["getCheckedByField", User::class],
    ];

    public function getHseIncidentReport()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUnsafeActionType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWorkArea()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPriority()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'hse_incident_report_id', 'invisible' => !true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'priority_id',],
            ['dataIndex' => 'work_area_id'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'assignee_1'],
            ['dataIndex' => 'opened_date'],
            ['dataIndex' => 'closed_date'],
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
