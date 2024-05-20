<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

abstract class AdminController extends Controller
{
    public function getType()
    {
        return "admin_permission";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $model, $type, $validateCreate, $validateUpdate;
    public function index()
    {
        $type = $this->type;
        $search = request('search');
        $pageLimit = request('page_limit');
        if ($pageLimit === null) $pageLimit = 10;
        $data = App::make($this->model)::search($search)->query(function ($q) {
            $q->orderBy('id', 'asc');
        })->paginate($pageLimit);
        return view('admin.render.index')->with(compact('data', 'pageLimit', 'search', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->validateCreate);
        App::make($this->model)::create($validated);
        toastr()->success('Create new role successfully!', 'Create new role');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = App::make($this->model)::find($id);
        $type = $this->type;
        return response()->json(['data' => $data, 'type' => $type], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->validateUpdate);
        try {
            $data = App::make($this->model)::find($id);
            $data->update($validated);
            toastr()->success('Update role successfully!', 'Update role');
        } catch (\Throwable $th) {
            toastr()->warning('Update role failed!', 'Update role');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = App::make($this->model)::find($id);
            $data->delete();
            return response()->json(['message' => 'Delete Role Successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 404);
        }
    }
}
