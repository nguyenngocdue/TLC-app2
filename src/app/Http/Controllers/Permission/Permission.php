<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Models\Permission as ModelsPermission;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class Permission extends Controller
{
    protected $model = ModelsPermission::class;

    public function __construct()
    {
    }

    public function getType()
    {
        return "permission";
    }

    public function getColumns()
    {
        return [
            [
                'dataIndex' => 'action',
                'renderer' => 'action',
                'type' => 'trash',
                'align' => 'center',
            ],
            [
                'dataIndex' => 'id',
                'renderer' => 'id',
                'type' => 'permissions2',
            ],
            [
                'dataIndex' => 'name',
            ],
            [
                'dataIndex' => 'guard_name',
                'align' => 'center',
            ],
        ];
    }

    public function index()
    {
        $search = request('search');
        $pageLimit = request('page_limit');

        $data = App::make($this->model)::search($search)
            ->query(fn ($q) => $q->orderBy('name', 'asc'))
            ->paginate($pageLimit);

        $dataSource = $data;
        // dd($dataSource);

        return view('permission/permission', [
            'columns' => $this->getColumns(),
            'dataSource' => $dataSource,
        ]);
    }

    public function edit($id)
    {
        $columns = [
            [
                'dataIndex' => 'name',
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                'dataIndex' => 'guard_name',
                'renderer' => 'text',
                'editable' => true,
            ],
        ];

        $dataSource = App::make($this->model)::where('id', $id)->get();
        // dd($dataSource);

        return view('permission/permission-edit', [
            'id' => $id,
            'columns' => $columns,
            'dataSource' => $dataSource,
        ]);
    }

    public function store(Request $request)
    {
        $name = $request->input('name')[0];
        $names = explode("|", $name);
        foreach ($names as $name) App::make($this->model)::create(['name' => $name]);
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $object = App::make($this->model)::where('id', $id)->get()[0];
        $data = $request->input();
        unset($data["_token"]);
        unset($data["_method"]);

        foreach (array_keys($data) as $key) $object->$key = $data[$key][0];
        $object->save();
        return redirect(route('permissions2.index'));
    }

    public function destroy($id)
    {
        $object = App::make($this->model)::where('id', $id)->get()[0];
        $object->destroy($id);
        return redirect(route('permissions2.index'));
    }
}
