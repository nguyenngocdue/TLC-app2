<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

use function GuzzleHttp\json_decode;

class ManageUserController extends Controller
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
        $patch = storage_path() . "/json/user/manage.json";
        if( !file_exists($patch)){
            $columnNames = Schema::getColumnListing('users');
            $columnTypes = [];
            foreach ($columnNames as $columnName){
                $type = Schema::getColumnType('users', $columnName);
                array_push($columnTypes, $type);
            }
            return view('dashboards.users.manageprop')->with(compact('columnNames','columnTypes'));
        }
        else{
            $dataManageUser = json_decode(file_get_contents($patch), true);
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
                $columnNames[$key] = $data['column_name'];
                $columnTypes[$key] = $data['column_type'];
                $columnLabels[$key] = $data['label'];
                $columnControls[$key] = $data['control'];
                $columnColSpans[$key] = $data['col_span'];
                $columnNewLines[$key] = $data['new_line'];
                $colorLines[$key] = $data['type_line'];
            }
            $diff1 = array_diff($columnNames,Schema::getColumnListing('users'));
            $diff2 = array_diff(Schema::getColumnListing('users'),$columnNames);
            if(empty($diff1) && empty($diff2)){
                return view('dashboards.users.manageprop')->with(compact('names','columnNames','columnTypes','columnLabels','columnControls','columnColSpans','columnNewLines','colorLines'));
            }else if (empty($diff1)){
                foreach($diff2 as $value){
                    $names['_'.$value]='_'.$value;
                    $columnNames['_'.$value] = $value;
                    $type = Schema::getColumnType('users', $value);
                    $columnTypes['_'.$value] =$type;
                    $columnLabels['_'.$value] =null;
                    $columnControls['_'.$value] ="input";
                    $columnColSpans['_'.$value] = null;
                    $columnNewLines['_'.$value] = "false";
                    $colorLines['_'.$value] = "new";
                }
                return view('dashboards.users.manageprop')->with(compact('names','columnNames','columnTypes','columnLabels','columnControls','columnColSpans','columnNewLines','colorLines'));
            }else{
                foreach($diff1 as $value){
                    $colorLines['_'.$value] = "removed";
                }
                return view('dashboards.users.manageprop')->with(compact('names','columnNames','columnTypes','columnLabels','columnControls','columnColSpans','columnNewLines','colorLines'));
            }
        }
    }
    public function store(Request $request){
        $data = $request->input();
        $maganeUser = [];
        foreach ($data['name'] as $key => $name){
            $array = [];
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
            Storage::disk('json')->put('manage.json',$jsonManageUser);
            Toastr::success('Save file json successfully', 'Save file json');
            return back();
        } catch (\Throwable $th) {
            Toastr::warning('$th', 'Save file json');
        }
    }
    public function destroy($name){
        dd($name);
        $patch = storage_path() . "/json/user/manage.json";
        $dataManageUser = json_decode(file_get_contents($patch), true);

        

    }
}
