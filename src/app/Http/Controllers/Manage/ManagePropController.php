<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Services\Manage\ManageService;
use App\Utils\Support\Table;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class ManagePropController extends Controller
{
    protected $type = "";
    protected $typeModel = "";
    protected $manageService;
    public function __construct(ManageService $manageService)
    {
        $this->manageService = $manageService;
    }
    public function getType()
    {
        return $this->type;
    }

    private function getColumns()
    {
        $controls0 = json_decode(file_get_contents(storage_path() . '/json/configs/view/dashboard/props/controls.json'), true)['controls'];
        $controls = array_map(fn ($control) => ["title" => Str::pretty($control), "value" => $control], $controls0);
        return [
            [
                "title" => "Action",
                "dataIndex" => "action",
            ],
            [
                "title" => "Name",
                "dataIndex" => "name",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "title" => "Column Name",
                "dataIndex" => "column_name",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "title" => "Column Type",
                "dataIndex" => "column_type",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "title" => "Label",
                "dataIndex" => "label",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "title" => "Control",
                "dataIndex" => "control",
                "editable" => true,
                "renderer" => "dropdown",
                "dataSource" => $controls,
            ],
            [
                "title" => "Col Span",
                "dataIndex" => "col_span",
                "editable" => true,
                "renderer" => "number",
            ],
            [
                "title" => "Hidden View All",
                "dataIndex" => "hidden_view_all",
                "editable" => true,
                "renderer" => "dropdown",
            ],
            [
                "title" => "Hidden Edit",
                "dataIndex" => "hidden_edit",
                "editable" => true,
                "renderer" => "dropdown",
            ],
            [
                "title" => "New Line",
                "dataIndex" => "new_line",
                "editable" => true,
                "renderer" => "dropdown",
            ],
            [
                "title" => "Validation",
                "dataIndex" => "validation",
                "editable" => true,
                "renderer" => "text",
            ],
            [
                "title" => "Frozen Left",
                "dataIndex" => "frozen_left",
                "editable" => true,
                "renderer" => "dropdown",
            ],
            [
                "title" => "Frozen Right",
                "dataIndex" => "frozen_right",
                "editable" => true,
                "renderer" => "dropdown",
            ],

        ];
    }

    private function makeBlankResultObject()
    {
        $columnNames = Table::getColumnNames(Str::plural($this->type));
        $columnTypes = Table::getColumnTypes(Str::plural($this->type));

        $result = [];
        foreach ($columnNames as $key => $value) {
            $result["_$value"] = [
                "name" => "_$value",
                "column_name" => $value,
                "column_type" => $columnTypes[$key],
                "label" => Str::pretty($value),
                "col_span" => 12,
            ];
        }
        return $result;
    }

    private function getMany2ManyProps()
    {
        $columnEloquentParams = App::make($this->typeModel)->eloquentParams;

        $result = [];
        foreach ($columnEloquentParams as $elqName => $elqValue) {
            if ($elqValue[0] === 'belongsToMany') {
                $result["_$elqName"] = [
                    "name" => "_$elqName",
                    "column_name" => "$elqName",
                    "column_type" => "ELQ(belongsToMany)",
                    "col_span" => 12,
                ];
            }
            if ($elqValue[0] === 'hasMany') {
                $result["_$elqName"] = [
                    "name" => "_$elqName",
                    "column_name" => "$elqName",
                    "column_type" => "ELQ(hasMany)",
                    "col_span" => 12,
                ];
            }
        }

        return $result;
    }

    private function addGreenAndRedColor($a, $b)
    {
        $toBeGreen = array_diff_key($a, $b);
        $toBeRed = array_diff_key($b, $a);

        return [$toBeGreen, $toBeRed];
    }

    private function getDataSource()
    {
        $result0 = $this->makeBlankResultObject();
        $result1 = $this->getMany2ManyProps();
        $result = array_merge($result0, $result1);
        $json = $this->manageService->path($this->type, 'props');
        [$toBeGreen, $toBeRed] = $this->addGreenAndRedColor($result, $json);

        foreach ($json as $key => $columns) {
            $result[$key]["name"] = $key;
            foreach ($columns as $column => $value) {
                //Ignore some JSON fields by the truth in Blank Result Object
                if (in_array($column, ['column_name', 'column_type'])) continue;
                $result[$key][$column] = $value;
            }
        }

        foreach (array_keys($toBeGreen) as $key) $result[$key]['row_color'] = "green";
        foreach (array_keys($toBeRed) as $key) $result[$key]['row_color'] = "red";

        return $result;
    }

    public function index()
    {
        $type = $this->type;
        $columns = $this->getColumns();
        $dataSourceWithKey = $this->getDataSource();
        $dataSource = array_values($dataSourceWithKey);
        return view('dashboards.props.manageprop')->with(compact('type', 'columns', 'dataSource'));
    }

    public function store(Request $request)
    {
        $data = $request->input();
        $result = [];
        $columns = $this->getColumns();
        $columns = array_filter($columns, fn ($column) => !in_array($column['dataIndex'], ['color', 'action']));

        foreach ($data['name'] as $key => $name) {
            $array = [];
            foreach ($columns as $column) {
                $value = $data[$column['dataIndex']][$key] ?? "";
                $array[$column['dataIndex']] = $value;
            }
            $result[$name] = $array;
        }
        try {
            $this->manageService->checkUploadFile($result, $this->type, 'props');
            return back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Save file json');
        }
    }
    public function destroy($name)
    {
        $res = $this->manageService->destroy($name, $this->type, 'props');
        if ($res) return response()->json(['message' => 'Successfully'], 200);
        return response()->json(['message' => 'Failed delete'], 404);
    }
}
