<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                'dataIndex' => "name",
                "renderer"  => 'read-only-text',
                'editable' => true,
            ],
            [
                'dataIndex' => "title",
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                'dataIndex' => "color",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ["", "slate", "zinc", "neutral", "stone", "amber", "yellow", "lime", "emerald", "teal", "cyan", "sky", "blue", "indigo", "violet", "purple", "fuchsia", "pink", "rose", "green", "orange", "red", "gray"],
                "sortBy" => "value",
            ],
            [
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
        return $json;
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
        // Log::info($dataSource);
        $str = json_encode($dataSource, JSON_PRETTY_PRINT);
        Storage::disk('json')->put($path, $str);
    }

    public function index()
    {
        $columns = $this->getColumns();
        $dataSource = array_values($this->getDataSource());

        return view("workflow/manage-statuses")->with(compact('columns', 'dataSource'));
    }

    public function store(Request $request)
    {
        $dataSource = (array)$request->all();
        unset($dataSource["_token"]);
        $dataSource = $this->distributeArrayToObject($dataSource);
        // dd($dataSource);
        $this->setDataSource($dataSource);
        return redirect()->back();
    }

    public function create(Request $request)
    {
        $name = $request->input('name')[0];
        $names = explode("|", $name);
        $newItems = [];
        foreach ($names as $name) $newItems[$name] = ['name' => $name, 'title' => Str::headline($name)];

        $dataSource = $this->getDataSource()  + $newItems;
        // dd($dataSource);
        $this->setDataSource($dataSource);
        return redirect()->back();
    }
}
