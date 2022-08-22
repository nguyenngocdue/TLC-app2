<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ManageLineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $patch = storage_path() . "/json/user/manageline.json";
        $patchTableName = storage_path() . "/json/user/tablename.json";
        $tableNames = json_decode(file_get_contents($patchTableName), true);
        if( !file_exists($patch)){
            $columnNames = [];
            $columnTypes = [];
            $columnTableNames = [];
            foreach($tableNames as $key =>$tableName ){
                $columnNameTable = Schema::getColumnListing($tableName);
                foreach ($columnNameTable as $columnName){
                    $type = Schema::getColumnType($tableName, $columnName);
                    array_push($columnTypes, $type);
                    array_push($columnTableNames, $key);
                }
                $columnNames = array_merge($columnNames, $columnNameTable);
            }
        return view('dashboards.users.managelineprop')->with(compact('columnTableNames','columnNames','columnTypes'));
        }
        else{
            $dataManageUser = json_decode(file_get_contents($patch), true);
            $names = [];
            $columnNames = [];
            $columnTypes = [];
            $columnLabels = [];
            $columnControls = [];
            $columnColSpans = [];
            $columnWrapModes = [];
            foreach ($dataManageUser as $key => $data){
                array_push($names,$key);
                array_push($columnNames,$data[0]);
                array_push($columnTypes,$data[1]);
                array_push($columnLabels,$data[2]);
                array_push($columnControls,$data[3]);
                array_push($columnColSpans,$data[4]);
                array_push($columnWrapModes,$data[5]);
            }
            return view('dashboards.users.manageprop')->with(compact('names','columnNames','columnTypes','columnLabels','columnControls','columnColSpans','columnWrapModes'));
        }
    }
    public function store(Request $request){
        $data = $request->input();
        $maganeUser = [];
        foreach ($data['name'] as $key => $name){
            $array = [];
            array_push($array, $data['column_name'][$key]);
            array_push($array, $data['column_type'][$key]);
            array_push($array, $data['label'][$key]);
            array_push($array, $data['control'][$key]);
            array_push($array, $data['col_span'][$key]);
            array_push($array, $data['wrap_mode'][$key]);
            $maganeUser[$name] = $array;
        }
        $jsonManageUser = json_encode($maganeUser);
        try {
            Storage::disk('json')->put('manageline.json',$jsonManageUser);
            Toastr::success('Save file json successfully', 'Save file json');
            return back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Save file json');
        }
    }
}
