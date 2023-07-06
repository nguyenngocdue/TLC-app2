<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_task extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id'];

    protected static $statusless = true;

    public static $eloquentParams = [];

    public static $oracyParams = [
        "getDisciplinesOfTask()" => ["getCheckedByField", User_discipline::class],
        "getLodsOfTask()" => ["getCheckedByField", Term::class],
        "getChildrenSubTasks()" => ["getCheckedByField", Pj_sub_task::class],
    ];

    public function getChildrenSubTasks()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getDisciplinesOfTask()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getLodsOfTask()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public static function getTasksOfUser($uid)
    {
        $user = User::findFromCache($uid);
        $discipline_id = $user->discipline;

        $lods = Term::where('field_id', 139)->orderBy('name')->get();
        // dump($lods);
        $discipline = User_discipline::findFromCache($discipline_id);
        $tasks = $discipline->getTasksOfDiscipline()->sortBy('name');

        $tasks = $tasks->whereNotIn('id', ['mail_checking' => 4, 'meeting' => 5, 'other' => 6, 'training' => 8]);

        // $projects = Project::with('getSubProjects')->orderBy('name')->get();
        // foreach ($projects as $project) {
        // $subProjects = $project->getSubProjects->sortBy('name');
        // $subProjects = Sub_project::all();
        // foreach ($subProjects as $subProject) {
        // dump($subProject->name);
        $tree = [
            ["key" => 0, "name" => "Any Project"],
        ];
        foreach ($lods as $lod) {
            foreach ($tasks as $task) {
                $allLodsOfThisTask = $task->getLodsOfTask()->pluck('id')->toArray();
                if (in_array($lod->id, $allLodsOfThisTask)) {
                    $lodKey = "lod" . $lod->id;
                    $tree[$lodKey] = ['key' => $lodKey, "name" => $lod->name, "parent" => 0];
                    $taskKey = "task" . $task->id;
                    $tree[$taskKey] = ['key' => $taskKey, "name" => $task->name, "parent" => $lodKey];
                    $subTasks = $task->getChildrenSubTasks()->pluck('name', 'id');
                    // dump($subTasks);
                    foreach ($subTasks as $subTaskId => $subTaskName) {
                        $subTaskKey = "subtask" . $subTaskId;
                        $tree[$subTaskKey] = ["key" => $subTaskKey, "name" => $subTaskName, "parent" => $taskKey,];
                    }
                }
            }
        }
        // dump($tree);

        return $tree;
    }
}
