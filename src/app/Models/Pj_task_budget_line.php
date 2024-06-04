<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_task_budget_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "owner_id", "order_no", "project_id", "task_budget_id",
        "task_id", "phase_id", "budget_value", "discipline_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getDiscipline" => ['belongsTo', User_discipline::class, 'discipline_id'],
        "getProject" => ['belongsTo', Project::class, 'project_id'],
        "getParent" => ['belongsTo', Pj_task_budget::class, 'task_budget_id'],
        "getTask" => ['belongsTo', Pj_task::class, 'task_id'],
        "getPhase" => ['belongsTo', Pj_task_phase::class, 'phase_id'],
    ];

    public function getDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTask()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPhase()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true,],
            ["dataIndex" => 'id', 'invisible' => true],
            ["dataIndex" => 'task_budget_id', 'invisible' => true, 'value_as_parent_id' => true],
            ["dataIndex" => 'project_id', 'invisible' => true],
            ["dataIndex" => 'discipline_id', 'invisible' => true],
            ["dataIndex" => 'phase_id'],
            ["dataIndex" => 'task_id'],
            ["dataIndex" => 'budget_value', 'title' => "Budget Value (Hours)", 'footer' => 'agg_sum'],
        ];
    }
}
