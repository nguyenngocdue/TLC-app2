<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_task_budget extends ModelExtended
{
    protected $fillable = [
        "name", "description", "owner_id", "status",
        "project_id", "department_id",
    ];

    public static $statusless = true;
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $project = $this->getProject;
        $department = $this->getDepartment;
        return ($project ? $project->name : "?") . " - " . ($department ? $department->name : "?");
    }

    public static $eloquentParams = [
        "getDepartment" => ['belongsTo', Department::class, 'department_id'],
        "getProject" => ['belongsTo', Project::class, 'project_id'],
        "getLines" => ['hasMany', Pj_task_budget_line::class, 'task_budget_id'],
        // "getLinesHOF" => ['hasMany', Pj_task_budget_line::class, 'task_budget_id'],

    ];

    public function getDepartment()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    // public function getLinesHOF()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }
}
