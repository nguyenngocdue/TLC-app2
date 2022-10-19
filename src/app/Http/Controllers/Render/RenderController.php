<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

abstract class RenderController extends Controller
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
    public function index()
    {
        $type = Str::plural($this->type);
        $idUser = Auth::guard()->id();
        $userLogin = User::find($idUser);
        $search = request('search');
        $pageLimit = $userLogin->settings[$type]['page_limit'] ?? null;
        if ($pageLimit === null) $pageLimit = 10;
        $model = $this->typeModel;
        $post = Post::search($search);
        $users = App::make($model)::search($search)->query(function ($q) {
            $q->orderBy('id', 'asc');
        })->paginate($pageLimit);
        $path = storage_path() . "/json/entities/$type/props.json";
        $path2 = storage_path() . "/json/entities/$type/relationships.json";
        if (!file_exists($path) || !file_exists($path2)) {
            Toastr::warning('Please make settings ' . Str::plural($type) . ' before rendering!', 'Please setting ' . Str::plural($type));
            $data = [];
            $data2 = [];
            return view('dashboards.render.prop')->with(compact('data', 'data2', 'users', 'pageLimit', 'type', 'userLogin', 'search', 'model'));
        } else {
            $data = json_decode(file_get_contents($path), true);
            $data2 = json_decode(file_get_contents($path2), true);
            $filterRenders = array_filter($data, function ($value) {
                $check = $value['hidden_view_all'] ?? null;
                return $check !== "true";
            });
            $filterRelationships = array_filter($data2, function ($value) {
                $check = $value['hidden'] ?? null;
                return $check !== "true";
            });

            $var = [];
            foreach ($filterRenders as $key1 => $v1) {
                foreach ($filterRelationships as $key2 => $v2) {
                    if ($v1['column_name'] === $v2['param_2']) {
                        $v1['render'] = 'relationship';
                        $v1['render_detail'] = $v2;
                        $filterRenders[$key1] = $v1;
                        $var[$key2] = $v2;
                    } else {
                        if (!isset($v1['render'])) {
                            $v1['render'] = 'database';
                            $v1['render_detail'] = null;
                        }
                        $filterRenders[$key1] = $v1;
                    }
                }
            }
            $data = $filterRenders;
            $data2 = array_diff_key($data2, $var);
            return view('dashboards.render.prop')->with(compact('data', 'data2', 'users', 'pageLimit', 'type', 'userLogin', 'search', 'model'));
        }
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
            $page = $request->input('_entity_page');
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
            $result['page_limit'] = $var[$entity]['page_limit'];
        }
        $var[$entity] = $result;
        $user->settings = $var;
        $user->update();
        Toastr::success('Save settings json Users successfully', 'Save file json');
        return redirect()->back();
    }
}
