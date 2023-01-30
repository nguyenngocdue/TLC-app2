<?php

namespace App\Http\Controllers;

use App\Utils\Support\Json\SuperProps;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'renderer' => 'id', 'type' => 'hse_corrective_actions', 'align' => 'center'],
            ['dataIndex' => 'getHseIncidentReport', 'title' => 'Source Doc', 'renderer' => 'column', 'rendererParam' => 'name', 'readonly' => true],
            ['dataIndex' => 'name', 'title' => 'Name', 'renderer' => 'text', 'editable' => 1,],
            ['dataIndex' => 'priority_id', 'title' => 'Priority ID'],
            ['dataIndex' => 'getWorkArea', 'title' => 'Work Area', 'renderer' => 'column', 'rendererParam' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'getAssignee', 'title' => 'Assignee', 'renderer' => 'column', 'rendererParam' => 'name'],
            ['dataIndex' => 'opened_date'],
            ['dataIndex' => 'closed_date'],
            ['dataIndex' => 'status', "renderer" => "status", "align" => "center"],
            ['dataIndex' => 'unsafe_action_type_id', 'title' => 'Unsafe Action Type'],
        ];
    }

    public function appendDefaultRendererAsText(&$columns)
    {
        foreach ($columns as &$column) {
            if (isset($column['renderer'])) {
                if (in_array($column['renderer'], ['id', 'column', 'status'])) continue;
            }
            if (!isset($column['renderer'])) $column['renderer'] = 'text';
            if (!isset($column['editable'])) $column['editable'] = true;
        }
    }

    public function makeDropdownForStatus(&$columns, $model)
    {
        foreach ($columns as &$column) {
            if (isset($column['renderer']) && $column['renderer'] === 'status') {
                $column['renderer'] = 'dropdown';
                $column['editable'] = true;
                $column['cbbDataSource'] = $model::getAvailableStatuses();
            }
        }
    }

    private function makeCbbDataSourceFromModel($modelPath)
    {
        $all = $modelPath::all();
        // dump($all);
        $result = [];
        foreach ($all as $line) {
            $result[] = ['value' => $line->id, 'title' => $line->name];
        }
        return $result;
    }

    public function makeDropdownForBelongsTo(&$columns, $dummyInstance)
    {
        $eloquentParams = $dummyInstance->eloquentParams;
        foreach ($columns as &$column) {
            if (isset($column['renderer']) && $column['renderer'] === 'column') {
                $dataIndex = $column['dataIndex'];
                $eloquentParam = $eloquentParams[$dataIndex];
                if ($eloquentParam[0] === 'belongsTo') {
                    // dump($eloquentParam);
                    $modelPath = $eloquentParam[1];
                    $column['column_name'] = $eloquentParam[2];

                    $cbbDataSource = $this->makeCbbDataSourceFromModel($modelPath);
                    $column['renderer'] = 'dropdown';
                    $column['editable'] = true;
                    $column['cbbDataSource'] = $cbbDataSource;
                    $column['sortBy'] = 'title';
                } else {
                    dump("Not belongsTo");
                    dump($eloquentParam);
                }
            }
        }
    }

    public function index()
    {
        $model = "App\\Models\\Hse_corrective_action";
        $all = $model::all();
        $dummyInstance = new $model;
        $params = $this->getManyLineParams();
        $this->appendDefaultRendererAsText($params);
        $this->makeDropdownForStatus($params, $model);
        $this->makeDropdownForBelongsTo($params, $dummyInstance);

        dump($params);
        $tableDataSource = $all;

        return view(
            'welcome',
            [
                'tableColumns' => $params,
                'tableDataSource' => $tableDataSource,
            ]
        );
    }

    public function store(Request $request)
    {
        dd($request->input());
        return back();
    }
}
