<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\json_decode;

abstract class ManagePropController extends Controller
{
    protected $type = "";
    public function __construct()
    {
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $type = $this->type;
        $dataManage = $this->path();
        if (!$dataManage) {
            $columnNames = Schema::getColumnListing($type . 's');
            $columnTypes = [];
            foreach ($columnNames as $columnName) {
                $typeColumn = Schema::getColumnType($type . 's', $columnName);
                array_push($columnTypes, $typeColumn);
            }
            // dd($columnNames);
            return view('dashboards.props.manageprop')->with(compact('type', 'columnNames', 'columnTypes'));
        } else {
            $names = [];
            $columnNames = [];
            $columnTypes = [];
            $columnLabels = [];
            $columnControls = [];
            $columnColSpans = [];
            $columnNewLines = [];
            $colorLines = [];
            foreach ($dataManage as $key => $data) {
                $names[$key] = $key;
                $columnNames[$key] = $data['column_name'];
                $columnTypes[$key] = $data['column_type'];
                $columnLabels[$key] = $data['label'];
                $columnControls[$key] = $data['control'];
                $columnColSpans[$key] = $data['col_span'];
                $columnNewLines[$key] = $data['new_line'];
                $colorLines[$key] = $data['type_line'];
            }
            $diff1 = array_diff($columnNames, Schema::getColumnListing($type . 's'));
            $diff2 = array_diff(Schema::getColumnListing($type . 's'), $columnNames);
            if (empty($diff1) && empty($diff2)) {
                return view('dashboards.props.manageprop')->with(compact('type', 'names', 'columnNames', 'columnTypes', 'columnLabels', 'columnControls', 'columnColSpans', 'columnNewLines', 'colorLines'));
            } else {
                foreach ($diff2 as $value) {
                    $names['_' . $value] = '_' . $value;
                    $columnNames['_' . $value] = $value;
                    $typeColumn = Schema::getColumnType($type . 's', $value);
                    $columnTypes['_' . $value] = $typeColumn;
                    $columnLabels['_' . $value] = $value;
                    $columnControls['_' . $value] = "input";
                    $columnColSpans['_' . $value] = "12";
                    $columnNewLines['_' . $value] = "false";
                    $colorLines['_' . $value] = "new";
                }
                foreach ($diff1 as $value) {
                    $colorLines['_' . $value] = "removed";
                }
                return view('dashboards.props.manageprop')->with(compact('type', 'names', 'columnNames', 'columnTypes', 'columnLabels', 'columnControls', 'columnColSpans', 'columnNewLines', 'colorLines'));
            }

        }
    }
    public function store(Request $request)
    {
        $data = $request->input();
        $magane = [];
        foreach ($data['name'] as $key => $name) {
            $array = [];
            $array['column_name'] = $data['column_name'][$key];
            $array['column_type'] = $data['column_type'][$key];
            $array['label'] = $data['label'][$key];
            $array['control'] = $data['control'][$key];
            $array['col_span'] = $data['col_span'][$key];
            $array['new_line'] = $data['new_line'][$key];
            $array['type_line'] = "default";
            $magane[$name] = $array;
        }
        $jsonManage = json_encode($magane);
        try {
            Storage::disk('json')->put("entities/{$this->type}/props.json", $jsonManage, 'public');
            Toastr::success('Save file json successfully', 'Save file json');
            return back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Save file json');
        }
    }
    public function destroy($name)
    {
        $dataManage = $this->path();
        unset($dataManage[$name]);
        try {
            Storage::disk('json')->put("entities/{$this->type}/props.json", json_encode($dataManage), 'public');
            Toastr::success('Save file json successfully', 'Save file json');
            return response()->json(['message' => 'Successfully'], 200);
        } catch (\Throwable $th) {
            Toastr::warning('$th', 'Save file json');
            return response()->json(['message' => 'Failed delete'], 404);
        }
    }
    protected function path(){
        $path = storage_path() . "/json/entities/{$this->type}/props.json";
        if(file_exists($path)){
            $dataManage = json_decode(file_get_contents($path), true);
            return $dataManage;
        }else{
            return false;
        }
    }
}
