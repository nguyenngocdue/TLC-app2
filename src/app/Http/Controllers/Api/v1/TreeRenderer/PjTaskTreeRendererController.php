<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Http\Controllers\Controller;
use App\Models\Pj_sub_task;
use App\Models\Pj_task;
use App\Models\Pj_task_phase;
use App\Models\User;
use App\Models\User_discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PjTaskTreeRendererController extends _TreeRendererController
{
    private function getTasks($treeBodyObjectId)
    {
        $discipline = User_discipline::query()
            ->where('id', $treeBodyObjectId)
            ->with("getTasksOfDiscipline")
            ->first();
        $taskIds = $discipline->getTasksOfDiscipline->pluck('id')->toArray();
        $tasks = Pj_task::query()
            ->whereIn('id', $taskIds)
            ->with("getChildrenSubTasks")
            ->with("getLodsOfTask")
            ->get();
        return $tasks;
    }

    private function getDataSourceFromTasks($tasks)
    {
        $phases = Pj_task_phase::all();
        $subTasks = Pj_sub_task::all();
        $result = [];
        foreach ($tasks as $task) {
            $phaseIds = $task->getLodsOfTask->pluck('id')->toArray();
            $route = route("pj_tasks.edit", $task->id);

            $subTaskIds = $task->getChildrenSubTasks->pluck('id')->toArray();
            $sub_tasks =  $subTasks
                ->whereIn('id', $subTaskIds)
                ->values();

            $sub_task_str = $sub_tasks->map(function ($item) {
                if ($item) {
                    $route = route('pj_sub_tasks.edit', $item->id);
                    return "<a class='text-blue-600' title='$item->description' href='$route'>" . $item->name . "</a>";
                }
            })->join(", ");

            foreach ($phaseIds as $phaseId) {
                $item = [
                    // 'id' => $task->id,
                    'name' => "<a class='text-blue-600' href='$route' title='$task->description'>" . $task->name . "</a>",
                    'phase' => $phases->where('id', $phaseId)->first()->name,
                    'sub_tasks' => $sub_task_str,
                ];
                $result[] = $item;
            }
        }
        // Log::info($result);
        return $result;
    }

    private function getColumns()
    {
        return [
            // ['dataIndex' => 'id', 'title' => 'ID', 'width' => 100],
            ['dataIndex' => 'phase', 'width' => 100],
            ['dataIndex' => 'name', 'title' => 'Task Name', 'width' => 200],
            ['dataIndex' => 'sub_tasks', 'width' => 200],
        ];
    }

    function render(Request $request)
    {
        $treeBodyObjectId = $request->input('treeBodyObjectId');
        $users = User::query()
            ->where('discipline', $treeBodyObjectId)
            ->where('resigned', false)
            ->where('time_keeping_type', 2)
            ->with("getAvatar")
            ->get();

        $tasks = $this->getTasks($treeBodyObjectId);

        $renderer = view("components.renderer.view-all-tree-explorer.pj-task-renderer", [
            'treeBodyObjectId' => $treeBodyObjectId,
            'tasks' => $tasks,
            'users' => $users,
            'minioPath' => app()->pathMinio(),

            'columns' => $this->getColumns(),
            'dataSource' => $this->getDataSourceFromTasks($tasks),
        ]);
        return $renderer;
    }
}
