<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Utils\Support\Listeners;
use Illuminate\Http\Request;

abstract class AbstractListenerController extends Controller
{
    protected $type = "";
    protected $typeModel = "";
    protected $title = "Manage Listeners";

    public function getType()
    {
        return $this->type;
    }

    private function getColumns()
    {
        return [
            [
                "dataIndex" => "name",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "column_name",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "action",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "triggers",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "listen_to_fields",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "listen_to_attrs",
                "renderer" => "text",
                "editable" => true,
            ],

        ];
    }

    public function index()
    {
        $dataSource = Listeners::getAllOf($this->type);
        // dump($dataSource);

        return view("dashboards.pages.manage-listener", [
            'title' => $this->title,
            'type' => $this->type,
            'route' => route($this->type . '_ltn.store'),
            'columns' => $this->getColumns(),
            'dataSource' => array_values($dataSource),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->input();
        $columns = array_filter($this->getColumns(), fn ($column) => !in_array($column['dataIndex'], []));
        $result = Listeners::convertHttpObjectToJson($data, $columns);
        // $this->handleMoveTo($result);
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            Listeners::move($result, $direction, $name);
        }
        Listeners::setAllOf($this->type, $result);
        return back();
    }

    public function create(Request $request)
    {
        $name = $request->input('name')[0];
        $names = explode("|", $name);
        $newItems = [];
        foreach ($names as $name) $newItems[$name] = [
            'name' => "_" . $name,
            'column_name' => $name,
        ];

        $dataSource = Listeners::getAllOf($this->type) + $newItems;
        Listeners::setAllOf($this->type, $dataSource);
        return back();
    }
}
