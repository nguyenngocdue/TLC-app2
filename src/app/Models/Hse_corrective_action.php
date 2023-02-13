<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_corrective_action extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'slug', 'hse_incident_report_id', 'priority_id', 'work_area_id',
        'assignee', 'opened_date', 'closed_date', 'status', 'unsafe_action_type_id', 'order_no',
    ];
    protected $table = "hse_corrective_actions";

    public $eloquentParams = [
        'getHseIncidentReport' => ['belongsTo', Hse_incident_report::class, 'hse_incident_report_id'],
        'getWorkArea' => ['belongsTo', Work_area::class, 'work_area_id'],
        'getPriority' => ['belongsTo', Priority::class, 'priority_id'],
        'getAssignee' => ['belongsTo', User::class, 'assignee'],
        'getUnsafeActionType' => ['belongsTo', Term::class, 'unsafe_action_type_id'],
    ];

    public $oracyParams = [
        "getDefMonitors()" => ["getCheckedByField", User::class],
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

    public function getDefMonitors()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    // public function getManyLineParams0()
    // {
    //     return [
    //         ['dataIndex' => 'id', 'renderer' => 'id', 'type' => 'hse_corrective_actions', 'align' => 'center'],
    //         ['dataIndex' => 'getHseIncidentReport', 'title' => 'Source Doc', 'renderer' => 'column', 'rendererParam' => 'name'],
    //         ['dataIndex' => 'name', 'title' => 'Name'],
    //         ['dataIndex' => 'priority_id', 'title' => 'Priority ID'],
    //         ['dataIndex' => 'getWorkArea', 'title' => 'Work Area', 'renderer' => 'column', 'rendererParam' => 'name'],
    //         ['dataIndex' => 'description'],
    //         ['dataIndex' => 'getAssignee', 'title' => 'Assignee', 'renderer' => 'column', 'rendererParam' => 'name'],
    //         ['dataIndex' => 'opened_date'],
    //         ['dataIndex' => 'closed_date'],
    //         ['dataIndex' => 'status', "renderer" => "status", "align" => "center"],
    //         ['dataIndex' => 'unsafe_action_type_id', 'title' => 'Unsafe Action Type'],
    //     ];
    // }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            // ['dataIndex' => 'hse_incident_report_id', 'invisible' => !true, 'value_as_parent_id' => true],
            ['dataIndex' => 'hse_incident_report_id'],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'priority_id',],
            ['dataIndex' => 'work_area_id'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'assignee'],
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
            ['dataIndex' => 'name', 'value_as_parent_type' => true],
            ['dataIndex' => 'work_area_id', 'hidden' => !true],
            ['dataIndex' => 'assignee'],
            ['dataIndex' => 'opened_date'],
            ['dataIndex' => 'status',],
            ['dataIndex' => 'priority_id',],
            ['dataIndex' => 'unsafe_action_type_id',],
        ];
    }
}
