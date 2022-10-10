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
        $this->middleware("permission:{$this->permissionMiddleware['edit']}")->only('update');
        $this->middleware("permission:{$this->permissionMiddleware['delete']}")->only('destroy');
    }
    public function index()
    {
        $type = Str::plural($this->type);
        $idUser = Auth::guard()->id();
        $userLogin = User::find($idUser);
        $search = request('search');
        $pageLimit = request('page_limit');
        if ($pageLimit === null) $pageLimit = 20;
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
            return view('dashboards.render.prop')->with(compact('data', 'data2', 'users', 'pageLimit', 'type', 'userLogin', 'search', 'model'));
        }
    }
    public function update(Request $request, $id)
    {
        $data = $request->input();
        $data = array_diff_key($data, ['_token' => '', '_method' => 'PUT']);
        $user = User::find($id);
        $dataDefault = User::find($id)->getAttributes();
        $dataSettings = [];
        foreach ($dataDefault as $key => $value) {
            if (!array_key_exists('_' . $key, $data)) {
                $dataSettings['_' . $key] = $value;
            }
        }
        $user->settings = $dataSettings;
        $user->update();
        Toastr::success('Save settings json Users successfully', 'Save file json');
        return redirect()->back();
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
}
