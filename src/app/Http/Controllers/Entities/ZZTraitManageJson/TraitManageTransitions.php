<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Transitions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitManageTransitions
{
    private function getColumnsTransition()
    {
        $allStatuses = LibStatuses::getFor($this->type);
        // dump($allStatuses);
        $columns = array_map(fn ($i) => [
            "dataIndex" => $i['name'],
            'renderer' => 'checkbox',
            'editable' => true,
            'align' => 'center',
            'title' => $i['title'],
        ], $allStatuses);
        $firstColumn = [
            [
                "dataIndex" => 'name',
                'renderer' => 'status',
                'align' => 'center',
                'title' => 'Name',
            ],
            [
                "dataIndex" => 'name',
                'renderer' => 'read-only-text',
                'editable' => true,
                'align' => 'center',
                'title' => 'Key',
            ],
        ];
        $columns = array_merge($firstColumn, $columns);
        // dump($columns);
        return $columns;
    }

    private function getDataSourceTransition()
    {
        $allStatuses = LibStatuses::getFor($this->type);
        $dataInJson = Transitions::getAllOf($this->type);
        // dump($dataInJson);
        $result = [];
        foreach ($allStatuses as $status) {
            $name = $status['name'];
            if (isset($dataInJson[$name])) {
                $result[] = $dataInJson[$name];
            } else {
                $result[] = ['name' => $name];
            }
        }
        return $result;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexTransition(Request $request)
    {
        return view('dashboards.pages.manage-transition', [
            'title' => $this->getTitle($request),
            'type' => $this->type,
            'route' => route($this->type . '_tst.store'),
            'columns' => $this->getColumnsTransition(),
            'dataSource' => array_values($this->getDataSourceTransition($this->type)),
        ]);
    }

    public function storeTransition(Request $request)
    {
        $data = $request->input();
        $columns = array_filter($this->getColumnsTransition(), fn ($column) => !in_array($column['dataIndex'], ['action']));
        $result = Transitions::convertHttpObjectToJson($data, $columns);
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            Transitions::move($result, $direction, $name);
        }
        Transitions::setAllOf($this->type, $result);
        return back();
    }
}
