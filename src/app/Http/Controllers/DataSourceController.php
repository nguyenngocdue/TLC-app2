<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $relationship =  (object)[
            "_workplace" => [
                "column_name" => "getWorkplace",
                "eloquent" => "belongsTo",
                "param_1" => "App\\Models\\Workplace",
                "param_2" => "workplace",
                "param_3" => null,
                "param_4" => null,
                "param_5" => null,
                "param_6" => null,
                "label" => "Posts",
                "control" => "count",
                "col_span" => "12",
                "new_line" => "false",
                "type_line" => "default"
            ]
        ];


        // $path = storage_path("/json/entities/user/props.json");
        // $props = json_decode(file_get_contents($path), true);
        // $relationship = $props["_workplace"];

        $column_name = $relationship->{'_workplace'}['column_name'];
        $u = User::first()->eloquentParams;
        $related = $u[$column_name][1];
        $table = ($related)::first()->getTable();

        $dataSource = DB::table($table)->select('id', 'name')->get();
        return view('dashboards.render.edit')->with(compact('dataSource'));
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
