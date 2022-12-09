<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Utils\Support\Relationships;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class ManageRelationshipController extends Controller
{
    protected $type = "";
    protected $typeModel = "";
    public function __construct()
    {
    }

    public function getType()
    {
        return $this->type;
    }

    private function getColumns()
    {
        $controls = json_decode(file_get_contents(storage_path() . '/json/configs/view/dashboard/relationships/renderers.json'), true);
        $viewAllControls = $controls['renderers'];
        $editControls = $controls['edit'];
        return [
            [
                "dataIndex" => "action",
            ],
            [
                "dataIndex" => "name",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "relationship",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "renderer_view_all",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => $viewAllControls,
            ],
            [
                "dataIndex" => "renderer_view_all_param",
                "editable" => true,
                "renderer" => "text",
            ],
            [
                "dataIndex" => "renderer_edit",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => $editControls,
            ],
            [
                "dataIndex" => "renderer_edit_param",
                "editable" => true,
                "renderer" => "text",
            ],
            [
                "dataIndex" => "control_name",
                "editable" => true,
                "renderer" => "text",
            ],
        ];
    }

    private function makeBlankDefaultObject()
    {
        $columnEloquentParams = App::make($this->typeModel)->eloquentParams;
        $result = [];
        foreach ($columnEloquentParams as $elqName => $elqValue) {
            $rowDescription = join(" | ", $elqValue);
            $result["_$elqName"] = [
                "name" => "_$elqName",
                "eloquent" => $elqName,
                "relationship" => $elqValue[0],
                "rowDescription" => $rowDescription,
            ];
        }

        return $result;
    }

    private function addGreenAndRedColor($a, $b)
    {
        $toBeGreen = array_diff_key($a, $b);
        $toBeRed = array_diff_key($b, $a);

        return [$toBeGreen, $toBeRed];
    }

    private function renewColumn(&$a, $b, $column)
    {
        foreach (array_keys($a) as $key) {
            if (!isset($b[$key])) continue;
            $updatedValue = $b[$key][$column];
            if ($a[$key][$column] != $updatedValue) {
                $a[$key][$column] = $updatedValue;
                $a[$key]['row_color'] = 'blue';
            }
        }
    }

    private function getDataSource($type)
    {
        $result = $this->makeBlankDefaultObject($type);
        $json = Relationships::getAllOf($type);
        $this->renewColumn($json, $result, 'relationship');
        [$toBeGreen, $toBeRed] = $this->addGreenAndRedColor($result, $json);

        foreach ($json as $key => $columns) {
            $result[$key]["name"] = $key;
            foreach ($columns as $column => $value) {
                $result[$key][$column] = $value;
            }
        }

        foreach (array_keys($toBeGreen) as $key) $result[$key]['row_color'] = "green";
        foreach (array_keys($toBeRed) as $key) $result[$key]['row_color'] = "red";

        return $result;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $type = $this->type;
        $columns = $this->getColumns();
        $dataSourceWithKey = $this->getDataSource($type);
        $dataSource = array_values($dataSourceWithKey);
        return view('dashboards.pages.managerelationship')->with(compact('type', 'columns', 'dataSource'));
    }
    public function store(Request $request)
    {
        $data = $request->input();
        $result = [];
        $columns = $this->getColumns();
        $columns = array_filter($columns, fn ($column) => !in_array($column['dataIndex'], ['color', 'action']));

        if (isset($data['name'])) { //<< This to trigger to create a json file with an empty array
            foreach ($data['name'] as $key => $name) {
                $array = [];
                foreach ($columns as $column) {
                    $value = $data[$column['dataIndex']][$key] ?? "";
                    $array[$column['dataIndex']] = $value;
                }
                $result[$name] = $array;
            }
        }

        try {
            Relationships::setAllOf($this->type, $result);
            return back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Save file json');
        }
    }
}
