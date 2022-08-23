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
        $patchTableName = storage_path() . "/json/table/user/tablename.json";
        $tableNames = json_decode(file_get_contents($patchTableName), true);
        if( !file_exists($patch)){
            $columnNames = [];
            $columnTypes = [];
            $columnTableNames = [];
            foreach($tableNames as $key => $tableName ){
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
            $columnTableNames = [];
            $names = [];
            $columnNames = [];
            $columnTypes = [];
            $columnLabels = [];
            $columnControls = [];
            $columnColSpans = [];
            $columnNewLines = [];
            $colorLines = [];
            foreach ($dataManageUser as $key => $data){
                $names[$key] =$key;
                $columnTableNames[$key] = $data['table_name'];
                $columnNames[$key] = $data['column_name'];
                $columnTypes[$key] = $data['column_type'];
                $columnLabels[$key] = $data['label'];
                $columnControls[$key] = $data['control'];
                $columnColSpans[$key] = $data['col_span'];
                $columnNewLines[$key] = $data['new_line'];
                $colorLines[$key] = $data['type_line'];
            }
            $globalColumnNames = [];
            foreach($tableNames as $key =>$tableName ){
                $columnNameTable = Schema::getColumnListing($tableName);
                foreach($columnNameTable as $columnName){
                    $globalColumnNames[$key.'|'.$columnName] = $columnName;
                }
            }
            $diff1 = array_diff_key($columnNames,$globalColumnNames);
            $diff2 = array_diff_key($globalColumnNames,$columnNames);
            if(empty($diff1) && empty($diff2)){
                return view('dashboards.users.managelineprop')->with(compact('columnTableNames','names','columnNames','columnTypes','columnLabels','columnControls','columnColSpans','columnNewLines','colorLines'));
            }else if (empty($diff1)){
                foreach($diff2 as $key => $value){
                    $keyTableName = explode('|' , $key);
                    $names[$key]=$keyTableName[0].'|'.$value;
                    $columnTableNames[$key]=$keyTableName[0];
                    $columnNames[$key] = $value;
                    $type = Schema::getColumnType($tableNames[$keyTableName[0]], $value);
                    $columnTypes[$key] =$type;
                    $columnLabels[$key] =$value;
                    $columnControls[$key] ="input";
                    $columnColSpans[$key] = "12";
                    $columnNewLines[$key] = "false";
                    $colorLines[$key] = "new";
                }
                return view('dashboards.users.managelineprop')->with(compact('columnTableNames','names','columnNames','columnTypes','columnLabels','columnControls','columnColSpans','columnNewLines','colorLines'));
            }else{
                foreach($diff1 as $key => $value){
                    $colorLines[$key] = "removed";
                }
                return view('dashboards.users.managelineprop')->with(compact('columnTableNames','names','columnNames','columnTypes','columnLabels','columnControls','columnColSpans','columnNewLines','colorLines'));
            }
            return view('dashboards.users.managelineprop')->with(compact('columnTableNames','names','columnNames','columnTypes','columnLabels','columnControls','columnColSpans','columnNewLines','colorLines'));
        }
    }
    public function store(Request $request){
        $data = $request->input();
        $maganeUser = [];
        foreach ($data['name'] as $key => $name){
            $array = [];
            $array['table_name']= $data['table_name'][$key];
            $array['column_name']= $data['column_name'][$key];
            $array['column_type']= $data['column_type'][$key];
            $array['label']= $data['label'][$key];
            $array['control']= $data['control'][$key];
            $array['col_span']= $data['col_span'][$key];
            $array['new_line']= $data['new_line'][$key];
            $array['type_line']= "default";
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
    public function destroy($name){
        $patch = storage_path() . "/json/user/manageline.json";
        $dataManageUser = json_decode(file_get_contents($patch), true);
        unset($dataManageUser[$name]);
        try {
            Storage::disk('json')->put('manageline.json',json_encode($dataManageUser));
            Toastr::success('Save file json successfully', 'Save file json');
            return response()->json(['message' => 'Successfully'],200);
        } catch (\Throwable $th) {
            Toastr::warning('$th', 'Save file json');
            return response()->json(['message' => 'Failed delete'],404);
        }
    }
}
