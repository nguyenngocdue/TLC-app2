<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ManageStatusesController extends Controller
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
                'dataIndex' => "color_index",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ['100', '200', '300', '400', '500', '600', '700', '800', '900'],
                "sortBy" => "value",
            ],
            [
                'dataIndex' => "rendered",
                'renderer' => 'formatter',
                'formatterName' => 'statusColorRendered',
            ],
        ];
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

    public function index()
    {
        $columns = $this->getColumns();
        $dataSource = array_values(Statuses::getAll());
        $route = ("statuses");

        return view("workflow/manage-statuses")->with(compact('columns', 'dataSource', 'route'));
    }

    public function store(Request $request)
    {
        $dataSource = (array)$request->all();
        unset($dataSource["_token"]);
        $dataSource = $this->distributeArrayToObject($dataSource);
        // dd($dataSource);
        Statuses::setAll($dataSource);
        return redirect()->back();
    }

    public function create(Request $request)
    {
        $name = $request->input('name')[0];
        $names = explode("|", $name);
        $newItems = [];
        foreach ($names as $name) $newItems[$name] = ['name' => $name, 'title' => Str::headline($name)];

        $dataSource = Statuses::getAll()  + $newItems;
        // dd($dataSource);
        Statuses::setAll($dataSource);
        return redirect()->back();
    }
}
