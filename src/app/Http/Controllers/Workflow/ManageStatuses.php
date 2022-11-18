<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ManageStatuses extends Controller
{
    public function __construct()
    {
    }

    public function getType()
    {
        return "workflow";
    }

    private function getColumns()
    {
        return   [
            [
                'title' => "Name",
                'dataIndex' => "name",
                "renderer"  => 'read-only-text',
                'editable' => true,
            ],
            [
                'title' => "Title",
                'dataIndex' => "title",
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                'title' => "Color",
                'dataIndex' => "color",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ["", "slate", "zinc", "neutral", "stone", "amber", "yellow", "lime", "emerald", "teal", "cyan", "sky", "blue", "indigo", "violet", "purple", "fuchsia", "pink", "rose", "green", "orange", "red", "gray"],
                "sortBy" => "value",
            ],
            [
                'title' => "Rendered",
                'dataIndex' => "rendered",
                'renderer' => 'formatter',
                'formatterName' => 'statusColorRendered',
            ],
        ];
    }

    private function getDataSource()
    {
        $path = "workflow/statuses.json";
        $pathFrom = storage_path('json/' . $path);
        $json = json_decode(file_get_contents($pathFrom, true), true);
        return array_values($json);
    }

    private function distributeArrayToObject($array)
    {
        $result = [];
        foreach ($array as $index => $attributes) {
            foreach ($attributes as $key => $value) {
                $name = $array['name'][$key];
                $result[$name][$index] = $value;
            }
        }
        // Log::info($result);
        return $result;
    }

    private function setDataSource($dataSource)
    {
        $path = "workflow/statuses.json";
        $dataSource = (array)$dataSource->all();
        unset($dataSource["_token"]);
        $dataSource = $this->distributeArrayToObject($dataSource);
        // Log::info($dataSource);
        $str = json_encode($dataSource, JSON_PRETTY_PRINT);
        Storage::disk('json')->put($path, $str);
    }

    public function index()
    {
        $columns = $this->getColumns();
        $dataSource = $this->getDataSource();
        return view("workflow/manage-statuses")->with(compact('columns', 'dataSource'));
    }

    public function store(Request $request)
    {
        $this->setDataSource($request);
        // return $this->index();
        return redirect()->back();
    }
}
