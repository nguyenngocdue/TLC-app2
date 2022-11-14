<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Services\Manage\ManageService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class ManageRelationshipController extends Controller
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
        $controls0 = json_decode(file_get_contents(storage_path() . '/json/configs/view/dashboard/relationships/renderers.json'), true)['renderers'];
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
                "title" => "Relationship",
                "dataIndex" => "relationship",
                "render" => "read-only-text",
                "editable" => true,
            ],
            // [
            //     "title" => "Label",
            //     "dataIndex" => "label",
            //     "render" => "text",
            //     "editable" => true,
            // ],
            [
                "title" => "Renderer",
                "dataIndex" => "renderer",
                "editable" => true,
                "render" => "dropdown",
                "dataSource" => $controls,
            ],
            [
                "title" => "Renderer Param",
                "dataIndex" => "renderer_param",
                "editable" => true,
                "render" => "text",
            ],
            [
                "title" => "Control Name",
                "dataIndex" => "control_name",
                "editable" => true,
                "render" => "text",
            ],
            // [
            //     "title" => "Col Span",
            //     "dataIndex" => "col_span",
            //     "editable" => true,
            //     "render" => "number",
            // ],
            // [
            //     "title" => "Hidden View All",
            //     "dataIndex" => "hidden_view_all",
            //     "editable" => true,
            //     "render" => "dropdown",
            // ],
            // [
            //     "title" => "Hidden Edit",
            //     "dataIndex" => "hidden_edit",
            //     "editable" => true,
            //     "render" => "dropdown",
            // ],
        ];
    }

    private function makeBlankResultObject()
    {
        $columnEloquentParams = App::make($this->typeModel)->eloquentParams;
        // if (!isset($columnEloquentParams)) {
        //     $title = 'Warning Settings';
        //     $message = 'Eloquent Param is empty!';
        //     $type = 'warning';
        //     return view('components.feedback.result')->with(compact('title', 'message', 'type'));
        // }

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

    private function getDataSource($type)
    {
        $result = $this->makeBlankResultObject($type);
        $json = $this->manageService->path($type, 'relationships');
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
        return view('dashboards.props.managerelationship')->with(compact('type', 'columns', 'dataSource'));
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


        // if (isset($data['name'])) {
        //     foreach ($data['name'] as $key => $name) {
        //         $array = [];
        //         $array['relationship'] = $data['relationship'][$key];
        //         $array['eloquent'] = $data['eloquent'][$key];
        //         $array['param_1'] = $data['param_1'][$key];
        //         $array['param_2'] = $data['param_2'][$key];
        //         $array['param_3'] = $data['param_3'][$key];
        //         $array['param_4'] = $data['param_4'][$key];
        //         $array['param_5'] = $data['param_5'][$key];
        //         $array['param_6'] = $data['param_6'][$key];
        //         $array['label'] = $data['label'][$key];
        //         $array['renderer'] = $data['renderer'][$key];
        //         $array['renderer_param'] = $data['renderer_param'][$key];
        //         $array['control_name'] = $data['control_name'][$key];
        //         $array['col_span'] = $data['col_span'][$key];
        //         $array['hidden'] = $data['hidden'][$key];
        //         $array['new_line'] = $data['new_line'][$key];
        //         $array['type_line'] = "default";
        //         $result[$name] = $array;
        //     }
        // } else {
        //     $result = (object)[];
        // }
        try {
            $this->manageService->checkUploadFile($result, $this->type, 'relationships');
            return back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Save file json');
        }
    }
    public function destroy($name)
    {
        $res = $this->manageService->destroy($name, $this->type, 'relationships');
        if ($res) return response()->json(['message' => 'Successfully'], 200);
        return response()->json(['message' => 'Failed delete'], 404);
    }
}
