<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class ViewAllController extends Controller
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

    private function getPageLimit()
    {
        $type = Str::plural($this->type);
        $userLogin = Auth::user(); //User::find($idUser);
        $pageLimit = $userLogin->settings[$type]['page_limit'] ?? null;
        if ($pageLimit === null) $pageLimit = 10;
        return $pageLimit;
    }

    private function getDataSource($pageLimit, $search)
    {
        $model = $this->typeModel;
        $result = App::make($model)::search($search)->query(function ($q) {
            $q->orderBy('id', 'asc');
        })->paginate($pageLimit);
        return $result;
    }

    private function getColumns($type)
    {
        function createObject($prop)
        {
            $allowControls = ['id', 'avatar-name'];
            $output = [
                'title' => $prop['label'],
                'dataIndex' => $prop['column_name'],
            ];
            if (in_array($prop['control'], $allowControls)) $output['render'] = $prop['control'];
            return $output;
        }

        $propsPath = storage_path() . "/json/entities/$type/props.json";
        if (!file_exists($propsPath)) return false;
        $props = json_decode(file_get_contents($propsPath), true);
        $props = array_filter($props, fn ($prop) => !$prop['hidden_view_all']);
        $result = array_values(array_map(fn ($prop) => createObject($prop), $props));
        return $result;
    }

    private function renderMeaningful(&$columns, $rawDataSource)
    {
        // Log::info($columns);
        // Log::info($rawDataSource);
        $eloquentParams = App::make($this->typeModel)->eloquentParams;
        $path = storage_path() . "/json/entities/{$this->type}/relationships.json";
        $json = json_decode(file_get_contents($path), true);
        // Log::info($json);

        foreach ($columns as &$column) {
            $dataIndex = $column['dataIndex'];
            if (isset($eloquentParams[$dataIndex])) {
                $relationship = $eloquentParams[$dataIndex][0];
                // Log::info($dataIndex . " " . $relationship);
                if ($relationship === 'belongsToMany') {
                    $relationshipJson = $json["_{$dataIndex}"];
                    // Log::info($relationshipJson);
                    $column['renderer'] = $relationshipJson['renderer'];
                    $column['rendererParam'] = $relationshipJson['renderer_param'];
                }
            }
        }
        return $rawDataSource;
    }

    public function index()
    {
        $type = Str::plural($this->type);
        $columns = $this->getColumns($type);

        $pageLimit = $this->getPageLimit();
        $search = request('search');
        $rawDataSource = $this->getDataSource($pageLimit, $search);

        $dataSource = $this->renderMeaningful($columns, $rawDataSource);
        // Log::info($columns);

        return view('dashboards.pages.viewAll2')->with(compact('pageLimit', 'type', 'search', 'columns', 'dataSource'));
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

    public function update(Request $request, $id)
    {
        $data = $request->input();
        $entity = $request->input('_entity');
        if (!$entity) {
            $page
                = $request->input('_entity_page');
            $data = array_diff_key($data, ['_token' => '', '_method' => 'PUT', '_entity_page' => '']);
            $user = User::find($id);
            $var = $user->settings;
            // $result = [];
            foreach ($data as $key => $value) {
                $var[$page][$key] = $value;
            }
            $user->settings = $var;
            $user->update();
            Toastr::success('Save settings Page Limit Users successfully', 'Save file json');
            return redirect()->back();
        }
        $data = array_diff_key($data, ['_token' => '', '_method' => 'PUT', '_entity' => '']);
        $user = User::find($id);
        $var = $user->settings;
        $result = [];
        foreach ($data as $key => $value) {
            $result['columns'][$key] = $value;
            $result['page_limit'] = $var[$entity]['page_limit'] ?? '';
        }
        $var[$entity] = $result;
        $user->settings = $var;
        $user->update();
        Toastr::success('Save settings json Users successfully', 'Save file json');
        return redirect()->back();
    }
}
