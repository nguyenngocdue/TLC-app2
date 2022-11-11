<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Services\Manage\ManageService;
use App\Utils\Support\Table;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PDO;

abstract class ManagePropController extends Controller
{
    protected $type = "";
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
                "render" => "read-only-text",
                "editable" => true,
            ],
            [
                "title" => "Column Name",
                "dataIndex" => "column_name",
                "render" => "read-only-text",
                "editable" => true,
            ],
            [
                "title" => "Column Type",
                "dataIndex" => "column_type",
                "render" => "read-only-text",
                "editable" => true,
            ],
            [
                "title" => "Label",
                "dataIndex" => "label",
                "render" => "text",
                "editable" => true,
            ],
            [
                "title" => "Control",
                "dataIndex" => "control",
                "editable" => true,
                "render" => "dropdown",
                "dataSource" => $controls,
            ],
            [
                "title" => "Col Span",
                "dataIndex" => "col_span",
                "editable" => true,
                "render" => "number",
            ],
            [
                "title" => "Hidden View All",
                "dataIndex" => "hidden_view_all",
                "editable" => true,
                "render" => "dropdown",
            ],
            [
                "title" => "Hidden Edit",
                "dataIndex" => "hidden_edit",
                "editable" => true,
                "render" => "dropdown",
            ],
            [
                "title" => "New Line",
                "dataIndex" => "new_line",
                "editable" => true,
                "render" => "dropdown",
            ],
            [
                "title" => "Validation",
                "dataIndex" => "validation",
                "editable" => true,
                "render" => "text",
            ],
            [
                "title" => "Frozen Left",
                "dataIndex" => "frozen_left",
                "editable" => true,
                "render" => "dropdown",
            ],
            [
                "title" => "Frozen Right",
                "dataIndex" => "frozen_right",
                "editable" => true,
                "render" => "dropdown",
            ],

        ];
    }

    private function makeBlankResultObject($type)
    {
        $columnNames = Table::getColumnNames(Str::plural($type));
        $columnTypes = Table::getColumnTypes(Str::plural($type));

        $result = [];
        foreach ($columnNames as $key => $value) {
            $result["_$value"] = [
                "name" => "_$value",
                "column_name" => $value,
                "column_type" => $columnTypes[$key],
                "label" => Str::pretty($value),
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

    private function getDataSource($type)
    {
        $result = $this->makeBlankResultObject($type);
        $json = $this->manageService->path($type, 'props');
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

    public function index()
    {
        $type = $this->type;
        $columns = $this->getColumns();
        $dataSourceWithKey = $this->getDataSource($type);
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
