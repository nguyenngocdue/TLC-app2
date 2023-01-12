<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\JsonControls;
use App\Utils\Support\Props;
use App\Utils\Support\Relationships;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class AbstractViewAllController extends Controller
{
    protected $type = "";
    protected $typeModel = '';
    protected $permissionMiddleware;

    public function __construct()
    {
        $this->middleware("permission:{$this->permissionMiddleware['read']}")->only('index');
        $this->middleware("permission:{$this->permissionMiddleware['edit']}")->only('index', 'update');
        $this->middleware("permission:{$this->permissionMiddleware['delete']}")->only('index', 'update', 'destroy');
    }

    public function getType()
    {
        return $this->type;
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $pageLimit = $settings[$type]['page_limit'] ?? 20;
        $columnLimit = $settings[$type]['columns'] ?? null;
        return [$pageLimit, $columnLimit];
    }

    private function getDataSource($pageLimit)
    {
        $model = $this->typeModel;
        $search = request('search');
        $result = App::make($model)::search($search)
            ->query(fn ($q) => $q->orderBy('updated_at', 'desc'))
            ->paginate($pageLimit);
        return $result;
    }

    private function getColumns($type, $columnLimit)
    {
        function createObject($prop, $type)
        {
            $output = [
                'title' => $prop['label'],
                'dataIndex' => $prop['column_name'],
            ];
            if (isset($prop['width']) && $prop['width']) $output['width'] = $prop['width'];

            switch ($prop['control']) {
                case 'id':
                    //Attach type to generate hyperlink
                    $output['renderer'] = 'id';
                    $output['align'] = 'center';
                    $output['type'] = $type;
                    break;
                case 'toggle':
                    $output['renderer'] = "toggle";
                    $output['align'] = "center";
                    break;
                case "number":
                    $output['align'] = "right";
                    break;
                case "picker_datetime":
                case "picker_date":
                case "picker_time":
                case "picker_month":
                case "picker_week":
                case "picker_quarter":
                case "picker_year":
                case "picker_datetime":
                    $output['renderer'] = "date-time";
                    $output['align'] = "center";
                    $output['rendererParam'] = $prop['control'];
                    break;
                case "text":
                case "textarea":
                    $output['renderer'] = "text";
                    break;
                case "status":
                    $output['renderer'] = "status";
                    $output['align'] = 'center';
                    break;
                case "thumbnail":
                    $output['renderer'] = "thumbnail";
                    break;
                default:
                    break;
            }

            return $output;
        }

        $props = Props::getAllOf($type);
        $props = array_filter($props, fn ($prop) => !$prop['hidden_view_all']);
        $props = array_filter($props, fn ($prop) => $prop['column_type'] !== 'static');
        if ($columnLimit) {
            $allows = array_keys($columnLimit);
            $props = array_filter($props, fn ($prop) => in_array($prop['name'], $allows));
        }
        $result = array_values(array_map(fn ($prop) => createObject($prop, $type), $props));
        // Log::info($result);
        return $result;
    }

    private function attachRendererIntoColumn(&$columns)
    {
        // Log::info($columns);
        // Log::info($rawDataSource);
        $model = App::make($this->typeModel);
        $eloquentParams = $model->eloquentParams;
        $oracyParams = $model->oracyParams;
        // Log::info($eloquentParams);

        $json = Relationships::getAllOf($this->type);
        if (is_array($columns)) {
            foreach ($columns as &$column) {
                $dataIndex = $column['dataIndex'];
                if (!isset($eloquentParams[$dataIndex]) && !isset($oracyParams[$dataIndex])) continue; //<<Id, Name, Slug...

                if (isset($eloquentParams[$dataIndex])) {
                    $relationship = $eloquentParams[$dataIndex][0];
                    $allows = JsonControls::getViewAllEloquents();
                } elseif (isset($oracyParams[$dataIndex])) {
                    $relationship = $oracyParams[$dataIndex][0];
                    $allows = JsonControls::getViewAllOracies();
                }

                if (in_array($relationship, $allows)) {
                    // dd($json, $dataIndex, $json["_{$dataIndex}"]);
                    $relationshipJson = $json["_{$dataIndex}"];
                    // dump($relationshipJson);
                    $column['renderer'] = $relationshipJson['renderer_view_all'] ?? "";
                    $column['rendererParam'] = $relationshipJson['renderer_view_all_param'] ?? "";
                }
            }
        }
    }

    private function attachEloquentNameIntoColumn(&$columns)
    {
        $eloquentParams = App::make($this->typeModel)->eloquentParams;

        $eloquent = [];
        foreach ($eloquentParams as $key => $eloquentParam) {
            if (in_array($eloquentParam[0], ['belongsTo'])) {
                $eloquent[$eloquentParam[2]] = $key;
            }
        }
        // Log::info($eloquent);
        // Log::info($columns);

        $keys = array_keys($eloquent);
        if (is_array($columns)) {
            foreach ($columns as &$column) {
                if (in_array($column['dataIndex'], $keys)) {
                    $column['dataIndex'] = $eloquent[$column['dataIndex']];
                }
            }
        }
    }

    public function index()
    {
        [$pageLimit, $columnLimit] = $this->getUserSettings();
        // Log::info($columnLimit);

        $type = Str::plural($this->type);
        $columns = $this->getColumns($type, $columnLimit);
        $dataSource = $this->getDataSource($pageLimit);

        $this->attachEloquentNameIntoColumn($columns); //<< This must be before attachRendererIntoColumn
        $this->attachRendererIntoColumn($columns);

        $title = LibApps::getFor($type)['title'];
        // Log::info($columns);

        return view('dashboards.pages.viewAll2')->with(compact('title', 'pageLimit', 'type', 'columns', 'dataSource'));
    }

    public function destroy($id)
    {
        try {
            $model = $this->typeModel;
            $data = App::make($model)->find($id);
            $data->delete();
            return response()->json(['message' => 'Delete User Successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 404);
        }
    }

    // public function update(Request $request, $id)
    // {
    //     $data = $request->input();
    //     $entity = $request->input('_entity');
    //     if (!$entity) {
    //         $page = $request->input('_entity_page');
    //         $data = array_diff_key($data, ['_token' => '', '_method' => 'PUT', '_entity_page' => '']);
    //         $user = User::find($id);
    //         $var = $user->settings;
    //         foreach ($data as $key => $value) {
    //             $var[$page][$key] = $value;
    //         }
    //         $user->settings = $var;
    //         $user->update();
    //         Toastr::success('Save settings Page Limit Users successfully', 'Save file json');
    //         return redirect()->back();
    //     }
    //     $data = array_diff_key($data, ['_token' => '', '_method' => 'PUT', '_entity' => '']);
    //     $user = User::find($id);
    //     $var = $user->settings;
    //     $result = [];
    //     foreach ($data as $key => $value) {
    //         $result['columns'][$key] = $value;
    //         $result['page_limit'] = $var[$entity]['page_limit'] ?? '';
    //     }
    //     $var[$entity] = $result;
    //     $user->settings = $var;
    //     $user->update();
    //     Toastr::success('Save settings json Users successfully', 'Save file json');
    //     return redirect()->back();
    // }
}
