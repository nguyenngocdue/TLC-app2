<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use App\Models\Pj_task;
use App\Models\Pj_task_phase;
use App\Models\User_discipline;
use Illuminate\Support\Facades\Log;

trait TraitTableRendererManyLineMatrix
{
    private $tasks;
    private function getXAxis()
    {
        $phases = Pj_task_phase::query()
            ->whereNotIn('id', [
                221, //Leave
                219, //HOF
                222, //Public holiday
                220, //NZO
                230, //Mockup
            ])
            ->orderBy('order_no')
            ->get();
        $columns = $phases->map(function ($phase) {
            return [
                'dataIndex' => $phase->id,
                'title' => $phase->name,
            ];
        })->toArray();
        return [
            ['dataIndex' => 'task', 'width' => 300,],
            ...$columns
        ];
    }

    private function getYAxis()
    {
        $tasks = $this->tasks;
        $x = $tasks->pluck('name', 'id')->mapWithKeys(function ($item, $key) {
            return [$key => ['id' => $key, 'task' => $item,],];
        })->values()->toArray();
        return $x;
    }

    private function getTasks($parentItem)
    {
        $disciplines = User_discipline::query()
            // ->where('show_in_task_budget', 1) //maybe
            ->whereHas('getDefAssignee', function ($q) use ($parentItem) {
                $q->where('department', $parentItem->department_id);
            })
            ->get();
        // dump($disciplines->pluck('name')->toArray());
        $disciplineIds = $disciplines->pluck('id')->toArray();
        // dump($disciplineIds);
        $tasks = Pj_task::query()
            ->whereHas('getDisciplinesOfTask', function ($q) use ($disciplineIds) {
                $q->whereIn('user_disciplines.id', $disciplineIds);
            })
            ->with("getLodsOfTask")
            ->orderBy('name')
            ->get();
        return $tasks;
    }

    private function getDataSource($xAxis, $yAxis)
    {
        $dataSource = [];
        foreach ($this->tasks as $task) {
            $phaseIds = $task->getLodsOfTask->pluck('id')->toArray();
            $line = ['task' => $task['name']];
            foreach ($xAxis as $col) {
                $index = $col['dataIndex'];
                // dump($index . " " . join(", ", $phaseIds));
                if (in_array($index, $phaseIds)) {
                    $line[$index] = (object)[
                        'value' => "<input class='w-full h-10 p-2 border border-gray-400 rounded text-right'>",
                        'cell_div_class' => '',
                    ];
                }
            }
            $dataSource[] = $line;
            // dump($task);
        }
        return $dataSource;
    }

    private function renderManyLineMatrix($tableName, $paginatedDataSource, $lineModelPath, $columns, $editable, $instance, $isOrderable, $colName, $tableFooter, $parentItem)
    {
        $this->tasks = $this->getTasks($parentItem);
        $columns = $this->getXAxis();
        // $rows = $this->getYAxis();
        $rows = [];

        $dataSource = $this->getDataSource($columns, $rows);
        // dump($tableName);
        // dump($paginatedDataSource);
        // dump($lineModelPath);
        // dump($columns);
        // dump($editable);
        // dump($instance);
        // dump($isOrderable);
        // dump($colName);
        // dump($tableFooter);
        return view("components.controls.many-line-matrix", [
            'columns' => $columns,
            'dataSource' => $dataSource,
        ]);
    }
}
