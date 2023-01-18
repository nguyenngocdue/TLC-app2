<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\ActionButtons;
use App\Utils\Support\Transitions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitManageActionButtons
{
    private function getColumnsActionButton()
    {
        $columns = [
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
            [
                "dataIndex" => 'label',
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'tooltip',
                'renderer' => 'text',
                'editable' => true,
            ],
        ];
        return $columns;
    }

    private function getDataSourceActionButton()
    {
        $allStatuses = LibStatuses::getFor($this->type);
        $dataInJson = ActionButtons::getAllOf($this->type);
        $result = [];
        foreach ($allStatuses as $status) {
            $name = $status['name'];
            if (isset($dataInJson[$name])) {
                $result[] = $dataInJson[$name];
            } else {
                $result[] = ['name' => $name];
            }
        }
        // dump($allStatuses);
        // dump($dataInJson);
        // dump($result);
        return $result;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexActionButton(Request $request)
    {
        return view('dashboards.pages.manage-action-button', [
            'title' => $this->getTitle($request),
            'type' => $this->type,
            'route' => route($this->type . '_atb.store'),
            'columns' => $this->getColumnsActionButton(),
            'dataSource' => array_values($this->getDataSourceActionButton($this->type)),
        ]);
    }

    public function storeActionButton(Request $request)
    {
        $data = $request->input();
        $columns = array_filter($this->getColumnsActionButton(), fn ($column) => !in_array($column['dataIndex'], ['action']));
        $result = ActionButtons::convertHttpObjectToJson($data, $columns);
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            ActionButtons::move($result, $direction, $name);
        }
        ActionButtons::setAllOf($this->type, $result);
        return back();
    }
}
