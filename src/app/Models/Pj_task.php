<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_task extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'status', 'owner_id',
        'department_id'
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getDepartment" =>  ["belongsTo", Department::class, "department_id"],
        "getUser" =>  ["belongsTo", User::class, "user_id"],
    ];

    public static $oracyParams = [
        "getDisciplinesOfTask()" => ["getCheckedByField", User_discipline::class],
        "getLodsOfTask()" => ["getCheckedByField", Pj_task_phase::class],
        "getChildrenSubTasks()" => ["getCheckedByField", Pj_sub_task::class],
    ];

    public function getDepartment()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

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

        $tree = [
            ["key" => 0, "name" => "Any Project"],
        ];
        foreach ($lods as $lod) {
            foreach ($tasks as $task) {
                $allLodsOfThisTask = $task->getLodsOfTask()->pluck('id')->toArray();
                if (in_array($lod->id, $allLodsOfThisTask)) {
                    $lodKey = "lod_" . $lod->id;
                    $tree[$lodKey] = ['key' => $lodKey, "name" => $lod->name, "parent" => 0];
                    $taskKey = "task_" . $task->id;
                    $tree[$taskKey . "+" . $lodKey] = ['key' => $taskKey, "name" => $task->name, "parent" => $lodKey];
                    $subTasks = $task->getChildrenSubTasks()->pluck('name', 'id');
                    // dump($subTasks);
                    foreach ($subTasks as $subTaskId => $subTaskName) {
                        $subTaskKey = "subtask_" . $subTaskId;
                        $tree[$subTaskKey] = ["key" => $subTaskKey, "name" => $subTaskName, "parent" => $taskKey,];
                    }
                }
            }
        }
        // dump($tree);
        return $tree;
    }
}
