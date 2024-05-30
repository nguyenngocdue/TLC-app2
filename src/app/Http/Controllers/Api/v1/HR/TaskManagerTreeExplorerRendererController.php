<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\BigThink\Oracy;
use App\Http\Controllers\Controller;
use App\Models\Pj_sub_task;
use App\Models\Pj_task;
use App\Models\Pj_task_phase;
use App\Models\User;
use App\Models\User_discipline;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskManagerTreeExplorerRendererController extends Controller
{
    private function getTasks($disciplineId)
    {
        $discipline = User_discipline::query()
            ->where('id', $disciplineId)
            ->get();
        Oracy::attach("getTasksOfDiscipline()", $discipline);
        $taskIds = $discipline[0]->{"getTasksOfDiscipline()"};
        $tasks = Pj_task::query()
            ->whereIn('id', $taskIds)
            ->get();
        Oracy::attach("getChildrenSubTasks()", $tasks);
        Oracy::attach("getLodsOfTask()", $tasks);
        return $tasks;
    }

    private function getDataSourceFromTasks($tasks)
    {
        $phases = Pj_task_phase::all();
        $subTasks = Pj_sub_task::all();
        $result = [];
        foreach ($tasks as $task) {
            $phaseIds = $task->{"getLodsOfTask()"};
            // Log::info($phaseIds);
            $route = route("pj_tasks.edit", $task->id);

            $subTaskIds = $task->{"getChildrenSubTasks()"};
            $sub_tasks = $subTasks
                ->whereIn('id', $subTaskIds)
                ->pluck('name', 'id');

            $sub_task_str = $sub_tasks->mapWithKeys(function ($item, $key) {
                return [$key => "<a class='text-blue-600' href='" . route('pj_sub_tasks.edit', $key) . "'>" . $item . "</a>"];
            })->join(", ");

            foreach ($phaseIds as $phaseId) {

                $item = [
                    // 'id' => $task->id,
                    'name' => "<a class='text-blue-600' href='$route'>" . $task->name . "</a>",
                    'phase' => $phases->where('id', $phaseId)->first()->name,
                    // 'sub_tasks' => $subTaskIds->count(),
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
        $disciplineId = $request->input('disciplineId');
        $users = User::query()
            ->where('discipline', $disciplineId)
            ->where('resigned', false)
            ->where('time_keeping_type', 2)
            ->with("getAvatar")
            ->get();

        $tasks = $this->getTasks($disciplineId);

        $renderer = view("components.renderer.view-all-tree-explorer.task-manager-renderer", [
            'disciplineId' => $disciplineId,
            'tasks' => $tasks,
            'users' => $users,
            'minioPath' => app()->pathMinio(),

            'columns' => $this->getColumns(),
            'dataSource' => $this->getDataSourceFromTasks($tasks),
        ]);
        return $renderer;
    }

    function renderToJson(Request $request)
    {
        return ResponseObject::responseSuccess(htmlspecialchars($this->render($request) . ""));
    }
}
